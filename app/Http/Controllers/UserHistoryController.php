<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Leads;
use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class UserHistoryController extends Controller
{
    public function index($userId = null)
    {
        // 1. OPTIMIZATION: Sub-queries ko alag se prepare kiya taki query fast chale
        $callCounts = DB::table('calls')
            ->select('created_by', DB::raw('COUNT(id) as total'))
            ->groupBy('created_by');

        $feedbackCounts = DB::table('feddbacksheet')
            ->select('created_by', DB::raw('COUNT(id) as total'))
            ->groupBy('created_by');

        // Fetch only users with role_id = 4 with optimized join
        $users = User::where('users.role_id', 4)
            ->leftJoinSub($callCounts, 'c', 'c.created_by', '=', 'users.id')
            ->leftJoinSub($feedbackCounts, 'f', 'f.created_by', '=', 'users.id')
            ->select(
                'users.id',
                'users.name',
                'users.email',
                DB::raw('COALESCE(c.total, 0) + COALESCE(f.total, 0) as total_followups')
            )
            ->orderByDesc('total_followups')
            ->get();

        $selectedUser = null;
        $historyData = collect();

        // 2. User selection logic
        if ($userId) {
            $selectedUser = $users->firstWhere('id', $userId) ?? User::find($userId);
        }

        // 3. Merged History Logic (Lead, Order, Ticket with previous status check)
        if ($selectedUser) {

            // Fetch Calls (Always treated as Lead)
            $calls = DB::table('calls as c1')
                ->where('c1.created_by', $selectedUser->id)
                ->select(
                    'c1.id',
                    'c1.description as note',

                    // FIX: NULLIF ka use empty string ko handle karne ke liye
                    DB::raw("COALESCE(
                        NULLIF(c1.status, ''), 
                        (
                            SELECT c2.status FROM calls c2 
                            WHERE c2.lead_id = c1.lead_id 
                              AND c2.id < c1.id 
                              AND c2.status IS NOT NULL 
                              AND c2.status != '' 
                            ORDER BY c2.id DESC LIMIT 1
                        )
                    ) as status"),

                    'c1.created_at as action_date',
                    DB::raw("'Lead' as type"),
                    'c1.lead_id as reference_id'
                )->get();

            // Fetch Feedback (Ticket if action_comment has data, else Order)
            // Table name corrected as per your requirement: feddbacksheet
            $feedbacks = DB::table('feddbacksheet as f1')
                ->where('f1.created_by', $selectedUser->id)
                ->select(
                    'f1.id',

                    DB::raw("CASE 
                        WHEN f1.action_comment IS NOT NULL AND f1.action_comment != '' THEN f1.action_comment 
                        ELSE f1.comment 
                    END as note"),

                    // FIX: NULLIF ka use empty string ko handle karne ke liye
                    DB::raw("COALESCE(
                        NULLIF(f1.status, ''), 
                        (
                            SELECT f2.status FROM feddbacksheet f2 
                            WHERE f2.order_id = f1.order_id 
                              AND f2.id < f1.id 
                              AND f2.status IS NOT NULL 
                              AND f2.status != '' 
                            ORDER BY f2.id DESC LIMIT 1
                        )
                    ) as status"),

                    'f1.created_at as action_date',

                    DB::raw("CASE 
                        WHEN f1.action_comment IS NOT NULL AND f1.action_comment != '' THEN 'Ticket' 
                        ELSE 'Order' 
                    END as type"),

                    'f1.order_id as reference_id'
                )->get();

            // Merge and Sort
            $historyData = $calls->concat($feedbacks)->sortByDesc('action_date');
        }

        return view('`user.followup-history`', compact('users', 'selectedUser', 'historyData'));
    }

    // public function report(Request $request)
    // {
    //     $from = $request->from ?? now()->subMonths(1)->toDateString();
    //     $to   = $request->to ?? now()->toDateString();

    //     // 🔹 LEAD DATA
    //     $leadTotalUsers = DB::table('leads')
    //         ->whereBetween(DB::raw('DATE(create_at)'), [$from, $to])
    //         ->distinct('emp_id')
    //         ->count('emp_id');

    //     // New Lead Users (first time lead)
    //     $leadNewUsers = DB::table('leads as l1')
    //         ->whereBetween(DB::raw('DATE(l1.create_at)'), [$from, $to])
    //         ->whereNotExists(function ($q) {
    //             $q->select(DB::raw(1))
    //                 ->from('leads as l2')
    //                 ->whereRaw('l2.emp_id = l1.emp_id')
    //                 ->whereRaw('l2.create_at < l1.create_at');
    //         })
    //         ->distinct('emp_id')
    //         ->count('emp_id');

    //     $leadOldUsers = $leadTotalUsers - $leadNewUsers;

    //     // 🔹 ORDER DATA
    //     $orderUsers = DB::table('orders')
    //         ->select(
    //             'uid',
    //             DB::raw('COUNT(*) as total_orders'),
    //             DB::raw('MIN(order_date) as first_order'),
    //             DB::raw('MAX(order_date) as last_order')
    //         )
    //         ->whereBetween('order_date', [$from, $to])
    //         ->groupBy('uid')
    //         ->get();

    //     // 🔹 Total Users
    //     $orderTotalUsers = $orderUsers->count();


    //     // 🔹 OLD USERS (8 months se pehle wale - for retain check)
    //     $oldUsers = DB::table('orders')
    //         ->where('order_date', '<', now()->subMonths(8))
    //         ->pluck('uid')
    //         ->toArray();


    //     // 🔹 COUNTERS
    //     $orderNewUsers = 0;
    //     $retainUsers = 0;
    //     $loyalCount = 0;


    //     // 🔹 CLASSIFICATION (NO OVERLAP)
    //     foreach ($orderUsers as $user) {

    //         // ✅ Loyal (highest priority)
    //         if ($user->total_orders > 10) {
    //             $loyalCount++;
    //         }

    //         // ✅ Retain (old user + current range me order)
    //         elseif (in_array($user->uid, $oldUsers)) {
    //             $retainUsers++;
    //         }

    //         // ✅ New (first time user)
    //         else {
    //             $orderNewUsers++;
    //         }
    //     }

    //     return view('user.user-report', compact(
    //         'from',
    //         'to',
    //         'leadTotalUsers',
    //         'leadNewUsers',
    //         'leadOldUsers',
    //         'orderTotalUsers',
    //         'orderNewUsers',
    //         'retainUsers',
    //         'loyalCount'
    //     ));
    // }

    //new report function

    // public function report(Request $request)
    // {
    //     $from = $request->from ?? now()->startOfYear()->toDateString();

    //     $to   = $request->to ?? now()->endOfYear()->toDateString();

    //     // 🔹 LEAD DATA
    //     $leadTotalUsers = DB::table('leads')
    //         ->whereBetween(DB::raw('DATE(create_at)'), [$from, $to])
    //         ->distinct('emp_id')
    //         ->count('emp_id');

    //     $totalLeads = DB::table('leads')   ->where('is_converted', 0)
    //         ->whereBetween(DB::raw('DATE(create_at)'), [$from, $to])
    //         ->count();

    //     // New Lead Users (first time lead)
    //     $leadNewUsers = DB::table('leads as l1')
    //         ->whereBetween(DB::raw('DATE(l1.create_at)'), [$from, $to])
    //         ->whereNotExists(function ($q) {
    //             $q->select(DB::raw(1))
    //                 ->from('leads as l2')
    //                 ->whereRaw('l2.emp_id = l1.emp_id')
    //                 ->whereRaw('l2.create_at < l1.create_at');
    //         })
    //         ->distinct('emp_id')
    //         ->count('emp_id');

    //     $leadOldUsers = $leadTotalUsers - $leadNewUsers;

    //     // 🔹 ORDER DATA
    //     $orderUsers = DB::table('orders')
    //         ->select(
    //             'uid',
    //             DB::raw('COUNT(*) as total_orders'),
    //             DB::raw('MIN(order_date) as first_order'),
    //             DB::raw('MAX(order_date) as last_order')
    //         )
    //         ->whereBetween('order_date', [$from, $to])
    //         ->groupBy('uid')
    //         ->get();

    //     // 🔹 Total Users
    //     $orderTotalUsers = DB::table('orders')
    //         ->whereBetween('order_date', [$from, $to])
    //         ->distinct('uid')
    //         ->count('uid');

    //     $totalOrders =  DB::table('leads')   ->where('is_converted', 0)
    //         ->whereBetween(DB::raw('DATE(create_at)'), [$from, $to])
    //         ->count();



    //     // 🔹 OLD USERS (8 months se pehle wale - for retain check)
    //     $oldUsers = DB::table('orders')
    //         ->where('order_date', '<', now()->subMonths(8))
    //         ->pluck('uid')
    //         ->toArray();


    //     // 🔹 COUNTERS
    //     $orderNewUsers = 0;
    //     $retainUsers = 0;
    //     $loyalCount = 0;


    //     // 🔹 CLASSIFICATION (NO OVERLAP)
    //     foreach ($orderUsers as $user) {

    //         // ✅ Loyal (highest priority)
    //         if ($user->total_orders > 10) {
    //             $loyalCount++;
    //         }

    //         // ✅ Retain (old user + current range me order)
    //         elseif (in_array($user->uid, $oldUsers)) {
    //             $retainUsers++;
    //         }

    //         // ✅ New (first time user)
    //         else {
    //             $orderNewUsers++;
    //         }
    //     }

    //     if ($request->from && $request->to) {

    //         // 🔹 Filter se year nikalo
    //         $year = \Carbon\Carbon::parse($request->from)->year;
    //     } else {

    //         // 🔹 Default current year
    //         $year = now()->year;
    //     }
    //     $monthlyReports = [];

    //     for ($month = 1; $month <= 12; $month++) {

    //         $startDate = \Carbon\Carbon::create($year, $month, 1)
    //             ->startOfMonth()
    //             ->toDateString();

    //         $endDate = \Carbon\Carbon::create($year, $month, 1)
    //             ->endOfMonth()
    //             ->toDateString();

    //         /*
    // |--------------------------------------------------------------------------
    // | LEAD DATA
    // |--------------------------------------------------------------------------
    // */

    //         $leadTotal = DB::table('leads')
    //             ->whereBetween(DB::raw('DATE(create_at)'), [$startDate, $endDate])
    //             ->distinct('emp_id')
    //             ->count('emp_id');

    //         $totalMonthLeads = DB::table('leads')
    //             ->whereBetween(DB::raw('DATE(create_at)'), [$startDate, $endDate])
    //             ->count();

    //         $leadNew = DB::table('leads as l1')
    //             ->whereBetween(DB::raw('DATE(l1.create_at)'), [$startDate, $endDate])

    //             ->whereNotExists(function ($q) {

    //                 $q->select(DB::raw(1))
    //                     ->from('leads as l2')
    //                     ->whereRaw('l2.emp_id = l1.emp_id')
    //                     ->whereRaw('l2.create_at < l1.create_at');
    //             })

    //             ->distinct('emp_id')
    //             ->count('emp_id');

    //         $leadOld = $leadTotal - $leadNew;

    //         /*
    // |--------------------------------------------------------------------------
    // | ORDER DATA
    // |--------------------------------------------------------------------------
    // */

    //         $orderUsers = DB::table('orders')

    //             ->select(
    //                 'uid',
    //                 DB::raw('COUNT(*) as total_orders')
    //             )

    //             ->whereBetween('order_date', [$startDate, $endDate])

    //             ->groupBy('uid')

    //             ->get();

    //         $orderTotal = $orderUsers->count();

    //         $totalMonthOrders = DB::table('orders')
    //             ->whereBetween('order_date', [$startDate, $endDate])
    //             ->count();

    //         $oldUsers = DB::table('orders')
    //             ->where('order_date', '<', now()->subMonths(8))
    //             ->pluck('uid')
    //             ->toArray();

    //         $orderNew = 0;
    //         $retain = 0;
    //         $loyal = 0;

    //         foreach ($orderUsers as $user) {

    //             if ($user->total_orders > 10) {
    //                 $loyal++;
    //             } elseif (in_array($user->uid, $oldUsers)) {
    //                 $retain++;
    //             } else {
    //                 $orderNew++;
    //             }
    //         }

    //         $monthlyReports[] = [

    //             'month_name' => \Carbon\Carbon::create($year, $month, 1)->format('F Y'),

    //             'from' => $startDate,
    //             'to' => $endDate,

    //             'leadTotal' => $leadTotal,
    //             'leadNew' => $leadNew,
    //             'leadOld' => $leadOld,

    //             'orderTotal' => $orderTotal,
    //             'orderNew' => $orderNew,
    //             'retain' => $retain,
    //             'loyal' => $loyal,
    //             'totalMonthLeads' => $totalMonthLeads,
    //             'totalMonthOrders' => $totalMonthOrders,
    //         ];
    //     }

    //     return view('user.user-report', compact(
    //         'from',
    //         'to',
    //         'totalLeads',
    //         'totalOrders',
    //         'leadTotalUsers',
    //         'leadNewUsers',
    //         'leadOldUsers',
    //         'orderTotalUsers',
    //         'orderNewUsers',
    //         'retainUsers',
    //         'loyalCount',
    //         'monthlyReports'
    //     ));
    // }


    // public function getList(Request $request)
    // {
    //     $type = $request->type;
    //     $from = $request->from;
    //     $to   = $request->to;

    //     $query = DB::table('leads')
    //         ->whereBetween(DB::raw('DATE(create_at)'), [$from, $to]);


    //     if ($type == 'new') {
    //         $query->whereNotExists(function ($q) {
    //             $q->select(DB::raw(1))
    //                 ->from('leads as l2')
    //                 ->whereRaw('l2.u_id = leads.u_id')
    //                 ->whereRaw('l2.create_at < leads.create_at');
    //         });
    //     }

    //     if ($type == 'old') {
    //         $query->whereExists(function ($q) {
    //             $q->select(DB::raw(1))
    //                 ->from('leads as l2')
    //                 ->whereRaw('l2.u_id = leads.u_id')
    //                 ->whereRaw('l2.create_at < leads.create_at');
    //         });
    //     }

    //     // total = no extra condition

    //     $users = $query->select('user_name', 'email', 'mobile', 'create_at')
    //         ->orderBy('create_at', 'desc')
    //         ->get();

    //     return view('user.section.report-list', compact('users'))->render();
    // }

   public function getList(Request $request)
{
    $type = $request->type;
    $from = $request->from;
    $to   = $request->to;

    $users = collect();

    /*
    |--------------------------------------------------------------------------
    | LEAD USERS
    |--------------------------------------------------------------------------
    */

    if (in_array($type, ['total', 'new', 'old'])) {

        /*
        |--------------------------------------------------------------------------
        | TOTAL LEAD USERS
        |--------------------------------------------------------------------------
        */

        if ($type == 'total') {

            $userIds = DB::table('leads')
                ->whereBetween(DB::raw('DATE(create_at)'), [$from, $to])
                ->distinct()
                ->pluck('emp_id');
        }

        /*
        |--------------------------------------------------------------------------
        | NEW LEAD USERS
        |--------------------------------------------------------------------------
        */

        elseif ($type == 'new') {

            $userIds = DB::table('leads as l1')
                ->whereBetween(DB::raw('DATE(l1.create_at)'), [$from, $to])

                ->whereNotExists(function ($q) {

                    $q->select(DB::raw(1))
                        ->from('leads as l2')
                        ->whereRaw('l2.emp_id = l1.emp_id')
                        ->whereRaw('l2.create_at < l1.create_at');
                })

                ->distinct()
                ->pluck('l1.emp_id');
        }

        /*
        |--------------------------------------------------------------------------
        | OLD LEAD USERS
        |--------------------------------------------------------------------------
        */

        elseif ($type == 'old') {

            $newUserIds = DB::table('leads as l1')
                ->whereBetween(DB::raw('DATE(l1.create_at)'), [$from, $to])

                ->whereNotExists(function ($q) {

                    $q->select(DB::raw(1))
                        ->from('leads as l2')
                        ->whereRaw('l2.emp_id = l1.emp_id')
                        ->whereRaw('l2.create_at < l1.create_at');
                })

                ->distinct()
                ->pluck('l1.emp_id')
                ->toArray();

            $userIds = DB::table('leads')
                ->whereBetween(DB::raw('DATE(create_at)'), [$from, $to])

                ->whereNotIn('emp_id', $newUserIds)

                ->distinct()
                ->pluck('emp_id');
        }

        /*
        |--------------------------------------------------------------------------
        | LEAD USER LIST
        |--------------------------------------------------------------------------
        */

        $users = DB::table('leads')

            ->join('users', 'users.id', '=', 'leads.emp_id')

            ->whereBetween(DB::raw('DATE(leads.create_at)'), [$from, $to])

            ->whereIn('leads.emp_id', $userIds)

            ->select(
                'users.id',

                DB::raw('MAX(users.name) as user_name'),
                DB::raw('MAX(users.email) as email'),
                DB::raw('MAX(users.mobile_no) as mobile'),

                DB::raw('MAX(leads.create_at) as create_at')
            )

            ->groupBy('users.id')

            ->orderByDesc('create_at')

            ->get();
    }

    /*
    |--------------------------------------------------------------------------
    | ORDER USERS
    |--------------------------------------------------------------------------
    */

    else {

        $userIds = collect();

        /*
        |--------------------------------------------------------------------------
        | TOTAL ORDER USERS
        |--------------------------------------------------------------------------
        */

        if ($type == 'order_total') {

            $userIds = DB::table('orders')
                ->whereBetween('order_date', [$from, $to])

                ->distinct()

                ->pluck('uid');
        }

        /*
        |--------------------------------------------------------------------------
        | RETAIN USERS
        |--------------------------------------------------------------------------
        */

        elseif ($type == 'retain') {

            $oldUsers = DB::table('orders')
                ->where('order_date', '<', now()->subMonths(8))
                ->distinct()
                ->pluck('uid')
                ->toArray();

            $loyalUsers = DB::table('orders')
                ->whereBetween('order_date', [$from, $to])

                ->select('uid')

                ->groupBy('uid')

                ->havingRaw('COUNT(*) > 10')

                ->pluck('uid')
                ->toArray();

            $userIds = DB::table('orders')
                ->whereBetween('order_date', [$from, $to])

                ->whereIn('uid', $oldUsers)

                ->whereNotIn('uid', $loyalUsers)

                ->distinct()

                ->pluck('uid');
        }

        /*
        |--------------------------------------------------------------------------
        | LOYAL USERS
        |--------------------------------------------------------------------------
        */

        elseif ($type == 'loyal') {

            $userIds = DB::table('orders')
                ->whereBetween('order_date', [$from, $to])

                ->select('uid')

                ->groupBy('uid')

                ->havingRaw('COUNT(*) > 10')

                ->pluck('uid');
        }

        /*
        |--------------------------------------------------------------------------
        | NEW ORDER USERS
        |--------------------------------------------------------------------------
        */

        elseif ($type == 'order_new') {

            $oldUsers = DB::table('orders')
                ->where('order_date', '<', now()->subMonths(8))
                ->distinct()
                ->pluck('uid')
                ->toArray();

            $loyalUsers = DB::table('orders')
                ->whereBetween('order_date', [$from, $to])

                ->select('uid')

                ->groupBy('uid')

                ->havingRaw('COUNT(*) > 10')

                ->pluck('uid')
                ->toArray();

            $userIds = DB::table('orders')
                ->whereBetween('order_date', [$from, $to])

                ->whereNotIn('uid', $oldUsers)

                ->whereNotIn('uid', $loyalUsers)

                ->distinct()

                ->pluck('uid');
        }

        /*
        |--------------------------------------------------------------------------
        | ORDER USER LIST
        |--------------------------------------------------------------------------
        */

        $users = DB::table('orders')

            ->join('users', 'users.id', '=', 'orders.uid')

            ->whereBetween('orders.order_date', [$from, $to])

            ->whereIn('orders.uid', $userIds)

            ->select(
                'users.id',

                DB::raw('MAX(users.name) as user_name'),
                DB::raw('MAX(users.email) as email'),
                DB::raw('MAX(users.mobile_no) as mobile'),

                DB::raw('MAX(orders.order_date) as create_at')
            )

            ->groupBy('users.id')

            ->orderByDesc('create_at')

            ->get();
    }

    return view('user.section.report-list', compact('users'))->render();
}

public function report(Request $request)
{
    $from = $request->from ?? date('Y-m-01');
    $to   = $request->to ?? date('Y-m-d');

    $fromDate = $from . ' 00:00:00';
    $toDate   = $to . ' 23:59:59';

    

    $threeMonthsAgo = date('Y-m-d H:i:s', strtotime('-3 months', strtotime($fromDate)));

    $totalLeads = Leads::whereBetween('create_at', [$fromDate, $toDate])->count();

    $convertedLeads = Leads::where('is_converted', 1)
        ->whereBetween('create_at', [$fromDate, $toDate])
        ->count();

    $notConvertedLeads = Leads::where('is_converted', 0)
        ->whereBetween('create_at', [$fromDate, $toDate])
        ->count();

    $totalUserIds = Leads::whereBetween('create_at', [$fromDate, $toDate])
        ->pluck('emp_id')
        ->unique()
        ->filter()
        ->values();

    $totalUsers = $totalUserIds->count();

    $convertedUserIds = Leads::where('is_converted', 1)
        ->whereBetween('create_at', [$fromDate, $toDate])
        ->pluck('emp_id')
        ->unique()
        ->filter()
        ->values();

    $notConvertedUserIds = Leads::where('is_converted', 0)
        ->whereBetween('create_at', [$fromDate, $toDate])
        ->pluck('emp_id')
        ->unique()
        ->filter()
        ->values();

    $notConvertedOnlyUserIds = $notConvertedUserIds
        ->diff($convertedUserIds)
        ->values();

    $allLoyalUserIds = Leads::where('is_converted', 1)
        ->groupBy('emp_id')
        ->havingRaw('COUNT(*) >= 10')
        ->pluck('emp_id')
        ->unique()
        ->filter()
        ->values();

    /*
    |--------------------------------------------------------------------------
    | Converted
    |--------------------------------------------------------------------------
    */

    $convertedNewUserIds = User::whereIn('id', $convertedUserIds)
        ->whereBetween('created_at', [$fromDate, $toDate])
        ->pluck('id');

    $convertedNewUsers = $convertedNewUserIds->count();

    $convertedOldUserIds = $convertedUserIds
        ->diff($convertedNewUserIds);

    $convertedLoyalUserIds = $convertedOldUserIds
        ->intersect($allLoyalUserIds);

    $convertedLoyalUsers = $convertedLoyalUserIds->count();

    $convertedRetainUserIds = User::whereIn('id', $convertedOldUserIds)
        ->where('created_at', '<', $threeMonthsAgo)
        ->whereNotIn('id', $convertedLoyalUserIds)
        ->pluck('id');

    $convertedRetainUsers = $convertedRetainUserIds->count();

    $convertedRegularUsers = $convertedOldUserIds
        ->diff($convertedLoyalUserIds)
        ->diff($convertedRetainUserIds)
        ->count();

    $convertedRetainUsers = $convertedRetainUsers + $convertedRegularUsers;    

    /*
    |--------------------------------------------------------------------------
    | Not Converted
    |--------------------------------------------------------------------------
    */

    $notConvertedNewUserIds = User::whereIn('id', $notConvertedOnlyUserIds)
        ->whereBetween('created_at', [$fromDate, $toDate])
        ->pluck('id');

    $notConvertedNewUsers = $notConvertedNewUserIds->count();

    $notConvertedOldUserIds = $notConvertedOnlyUserIds
        ->diff($notConvertedNewUserIds);

    $notConvertedLoyalUserIds = $notConvertedOldUserIds
        ->intersect($allLoyalUserIds);

    $notConvertedLoyalUsers = $notConvertedLoyalUserIds->count();

    $notConvertedRetainUserIds = User::whereIn('id', $notConvertedOldUserIds)
        ->where('created_at', '<', $threeMonthsAgo)
        ->whereNotIn('id', $notConvertedLoyalUserIds)
        ->pluck('id');

    $notConvertedRetainUsers = $notConvertedRetainUserIds->count();

    $notConvertedRegularUsers = $notConvertedOldUserIds
        ->diff($notConvertedLoyalUserIds)
        ->diff($notConvertedRetainUserIds)
        ->count();

    $notConvertedRetainUsers = $notConvertedRetainUsers + $notConvertedRegularUsers;    

    $totalConvertedUsers = $convertedUserIds->count();
    $totalNotConvertedUsers = $notConvertedOnlyUserIds->count();

    return view('user.user-report', compact(
        'from',
        'to',
        'totalLeads',
        'convertedLeads',
        'notConvertedLeads',
        'totalUsers',

        'convertedNewUsers',
        'convertedRegularUsers',
        'convertedRetainUsers',
        'convertedLoyalUsers',

        'notConvertedNewUsers',
        'notConvertedRegularUsers',
        'notConvertedRetainUsers',
        'notConvertedLoyalUsers',
        'totalConvertedUsers',
        'totalNotConvertedUsers',
    ));
}

public function getReportUsers(Request $request)
{
    $type = $request->type;
    $from = $request->from;
    $to   = $request->to;

    $fromDate = $from . ' 00:00:00';
    $toDate   = $to . ' 23:59:59';

    $threeMonthsAgo = date('Y-m-d H:i:s', strtotime('-3 months', strtotime($fromDate)));

    $convertedUserIds = Leads::where('is_converted', 1)
        ->whereBetween('created_at', [$fromDate, $toDate])
        ->distinct()
        ->pluck('emp_id');

    $notConvertedUserIds = Leads::where('is_converted', 0)
        ->whereBetween('created_at', [$fromDate, $toDate])
        ->distinct()
        ->pluck('emp_id');

    $notConvertedOnlyUserIds = $notConvertedUserIds->diff($convertedUserIds);

    $allLoyalUserIds = Leads::where('is_converted', 1)
        ->select('emp_id')
        ->groupBy('emp_id')
        ->havingRaw('COUNT(*) >= 10')
        ->pluck('emp_id');

    switch ($type) {
        case 'converted_new':
            $userIds = User::whereIn('id', $convertedUserIds)
                ->whereBetween('created_at', [$fromDate, $toDate])
                ->pluck('id');
            break;

        case 'converted_regular':
            $newIds = User::whereIn('id', $convertedUserIds)
                ->whereBetween('created_at', [$fromDate, $toDate])
                ->pluck('id');

            $oldIds = $convertedUserIds->diff($newIds);
            $loyalIds = $oldIds->intersect($allLoyalUserIds);

            $retainIds = User::whereIn('id', $oldIds)
                ->where('created_at', '<', $threeMonthsAgo)
                ->whereNotIn('id', $loyalIds)
                ->pluck('id');

            $userIds = $oldIds->diff($loyalIds)->diff($retainIds);
            break;

        case 'converted_retain':
            $newIds = User::whereIn('id', $convertedUserIds)
                ->whereBetween('created_at', [$fromDate, $toDate])
                ->pluck('id');

            $oldIds = $convertedUserIds->diff($newIds);
            $loyalIds = $oldIds->intersect($allLoyalUserIds);

            $userIds = User::whereIn('id', $oldIds)
                ->where('created_at', '<', $threeMonthsAgo)
                ->whereNotIn('id', $loyalIds)
                ->pluck('id');
            break;

        case 'converted_loyal':
            $newIds = User::whereIn('id', $convertedUserIds)
                ->whereBetween('created_at', [$fromDate, $toDate])
                ->pluck('id');

            $oldIds = $convertedUserIds->diff($newIds);
            $userIds = $oldIds->intersect($allLoyalUserIds);
            break;

        case 'not_converted_new':
            $userIds = User::whereIn('id', $notConvertedOnlyUserIds)
                ->whereBetween('created_at', [$fromDate, $toDate])
                ->pluck('id');
            break;

        case 'not_converted_regular':
            $newIds = User::whereIn('id', $notConvertedOnlyUserIds)
                ->whereBetween('created_at', [$fromDate, $toDate])
                ->pluck('id');

            $oldIds = $notConvertedOnlyUserIds->diff($newIds);
            $loyalIds = $oldIds->intersect($allLoyalUserIds);

            $retainIds = User::whereIn('id', $oldIds)
                ->where('created_at', '<', $threeMonthsAgo)
                ->whereNotIn('id', $loyalIds)
                ->pluck('id');

            $userIds = $oldIds->diff($loyalIds)->diff($retainIds);
            break;

        case 'not_converted_retain':
            $newIds = User::whereIn('id', $notConvertedOnlyUserIds)
                ->whereBetween('created_at', [$fromDate, $toDate])
                ->pluck('id');

            $oldIds = $notConvertedOnlyUserIds->diff($newIds);
            $loyalIds = $oldIds->intersect($allLoyalUserIds);

            $userIds = User::whereIn('id', $oldIds)
                ->where('created_at', '<', $threeMonthsAgo)
                ->whereNotIn('id', $loyalIds)
                ->pluck('id');
            break;

        case 'not_converted_loyal':
            $newIds = User::whereIn('id', $notConvertedOnlyUserIds)
                ->whereBetween('created_at', [$fromDate, $toDate])
                ->pluck('id');

            $oldIds = $notConvertedOnlyUserIds->diff($newIds);
            $userIds = $oldIds->intersect($allLoyalUserIds);
            break;

        case 'converted_total':
            $userIds = $convertedUserIds;
            break;

        case 'not_converted_total':
            $userIds = $notConvertedOnlyUserIds;
            break;

        default:
            $userIds = collect([]);
    }

    $users = User::whereIn('id', $userIds)
        ->select('id', 'name', 'email', 'mobile_no', 'created_at')
        ->orderBy('created_at', 'desc')
        ->paginate(1000);

    return response()->json([
        'data' => $users->items(),
        'current_page' => $users->currentPage(),
        'last_page' => $users->lastPage(),
        'total' => $users->total(),
        'next_page_url' => $users->nextPageUrl(),
    ]);
}
public function analyticsReport($type)
{
    $fromDate   = request('from_date');
    $toDate     = request('to_date');

    // $search     = request('search');
    $search = request('name') ?? request('search');
    $countryFilter = request('country');
    switch ($type) {

        /*
        |--------------------------------------------------------------------------
        | Lead Tracking
        |--------------------------------------------------------------------------
        */
        case 'lead-tracking':

                $employeeId = request('employee_id', 'all');
                $range = request('range', 'last_month');

                // ===============================================
                // 0. Fetch Marketing Users (role_id = 4)
                // ===============================================
                // $marketingUsers = \App\Models\User::where('role_id', 4)
                //     ->pluck('name', 'id')
                //     ->toArray();

                $marketingUsers = \App\Models\User::where('role_id', 4)
                    ->when($search, function ($q) use ($search) {
                        $q->where('name', 'LIKE', "%{$search}%");
                    })
                    ->pluck('name', 'id')
                    ->toArray();

                $marketingUserIds = array_keys($marketingUsers);
                $marketingUserNames = array_values($marketingUsers);

                $selectedEmpName = null;

                // ===============================================
                // 1. Date Filtering Logic
                // ===============================================
                $startDate = null;
                $endDate = null;

                if ($range === 'today') {

                    $startDate = \Carbon\Carbon::today();
                    $endDate = \Carbon\Carbon::today()->endOfDay();

                } elseif ($range === 'yesterday') {

                    $startDate = \Carbon\Carbon::yesterday();
                    $endDate = \Carbon\Carbon::yesterday()->endOfDay();

                } elseif ($range === 'last_week') {

                    $startDate = \Carbon\Carbon::now()->subDays(7)->startOfDay();
                    $endDate = \Carbon\Carbon::now()->endOfDay();

                } elseif ($range === 'last_month') {

                    $startDate = \Carbon\Carbon::now()->subMonth()->startOfDay();
                    $endDate = \Carbon\Carbon::now()->endOfDay();

                } elseif (
                    $range === 'custom' &&
                    request('start_date') &&
                    request('end_date')
                ) {

                    $startDate = \Carbon\Carbon::parse(request('start_date'))->startOfDay();
                    $endDate = \Carbon\Carbon::parse(request('end_date'))->endOfDay();
                }

                if ($fromDate && $toDate) {
                    $startDate = \Carbon\Carbon::parse($fromDate)->startOfDay();
                    $endDate   = \Carbon\Carbon::parse($toDate)->endOfDay();
                }

            // ===============================================
            // 2. Worked Leads Query
            // ===============================================
            $workedQuery = \App\Models\Leads::query();

            if ($employeeId !== 'all') {

                $workedQuery->where('created_by', $employeeId);

                $selectedEmpName = \App\Models\User::where('id', $employeeId)
                    ->value('name');

            } else {

                $workedQuery->whereIn('created_by', $marketingUserIds);
            }

            if ($startDate && $endDate) {

                $workedQuery->whereBetween('created_at', [$startDate, $endDate]);
            }

            $workedData = $workedQuery
                ->selectRaw('created_by, COUNT(*) as worked')
                ->groupBy('created_by')
                ->get();

            // ===============================================
            // 3. Converted Leads Query
            // ===============================================
            $convertedQuery = \App\Models\Leads::query()
                ->join('orders', 'leads.order_id', '=', 'orders.order_id')
                ->where('leads.is_converted', 1)
                ->whereNotNull('leads.converted_at');

            if ($employeeId !== 'all') {

                $convertedQuery->where('orders.l_converted_by', $selectedEmpName);

            } else {

                $convertedQuery->whereIn('orders.l_converted_by', $marketingUserNames);
            }

            if ($startDate && $endDate) {

                $convertedQuery->whereBetween('leads.converted_at', [$startDate, $endDate]);
            }

            $convertedData = $convertedQuery
                ->selectRaw('orders.l_converted_by as emp_name, COUNT(*) as converted')
                ->groupBy('orders.l_converted_by')
                ->pluck('converted', 'emp_name')
                ->toArray();

            // ===============================================
            // 4. Final Table Data
            // ===============================================
            $data = [];

            foreach ($workedData as $row) {

                $name = $marketingUsers[$row->created_by] ?? 'Unknown';

                // =========================
                // Worked Leads List
                // =========================
                $workedLeadsQuery = \App\Models\Leads::where('created_by', $row->created_by);

                if ($startDate && $endDate) {

                    $workedLeadsQuery->whereBetween('created_at', [$startDate, $endDate]);
                }

                $workedLeads = $workedLeadsQuery
                    ->latest()
                    ->get();

                $referredUsersQuery = \App\Models\User::where('refer_id', $row->created_by);
                if ($startDate && $endDate) {
                    $referredUsersQuery->whereBetween('created_at', [$startDate, $endDate]);
                }

                $referredUsers = $referredUsersQuery
                    ->latest()
                    ->get();  

                // =========================
                // Converted Leads List
                // =========================
                $convertedLeadsQuery = \App\Models\Leads::join(
                        'orders',
                        'leads.order_id',
                        '=',
                        'orders.order_id'
                    )
                    ->where('orders.l_converted_by', $name)
                    ->where('leads.is_converted', 1)
                    ->select('leads.*');

                if ($startDate && $endDate) {

                    $convertedLeadsQuery->whereBetween(
                        'leads.converted_at',
                        [$startDate, $endDate]
                    );
                }

                $convertedLeads = $convertedLeadsQuery
                    ->latest('leads.converted_at')
                    ->get();

                $data[] = (object)[
                    'name' => ucwords($name),
                    'worked' => $row->worked,
                    'converted' => $convertedData[$name] ?? 0,
                    'referred' => $referredUsers->count(),

                    'worked_leads' => $workedLeads,
                    'converted_leads' => $convertedLeads,
                    'referred_users' => $referredUsers,
                ];
            }

            return view(
                'back-end.reports.analytics-table',
                [
                    'type' => 'Lead Tracking',
                    'data' => $data
                ]
            );

        break;

        /*
        |--------------------------------------------------------------------------
        | Country Data
        |--------------------------------------------------------------------------
        */
        case 'country':

    // ===============================================
    // Valid Country Codes Map
    // ===============================================
    $countries = [

        '+1' => 'USA/Canada',
        '1' => 'USA/Canada',

        '+7' => 'Russia/Kazakhstan',
        '7' => 'Russia/Kazakhstan',

        '+20' => 'Egypt',
        '20' => 'Egypt',

        '+27' => 'South Africa',
        '27' => 'South Africa',

        '+30' => 'Greece',
        '30' => 'Greece',

        '+31' => 'Netherlands',
        '31' => 'Netherlands',

        '+32' => 'Belgium',
        '32' => 'Belgium',

        '+33' => 'France',
        '33' => 'France',

        '+34' => 'Spain',
        '34' => 'Spain',

        '+36' => 'Hungary',
        '36' => 'Hungary',

        '+39' => 'Italy',
        '39' => 'Italy',

        '+40' => 'Romania',
        '40' => 'Romania',

        '+41' => 'Switzerland',
        '41' => 'Switzerland',

        '+43' => 'Austria',
        '43' => 'Austria',

        '+44' => 'United Kingdom',
        '44' => 'United Kingdom',

        '+45' => 'Denmark',
        '45' => 'Denmark',

        '+46' => 'Sweden',
        '46' => 'Sweden',

        '+47' => 'Norway',
        '47' => 'Norway',

        '+48' => 'Poland',
        '48' => 'Poland',

        '+49' => 'Germany',
        '49' => 'Germany',

        '+51' => 'Peru',
        '51' => 'Peru',

        '+52' => 'Mexico',
        '52' => 'Mexico',

        '+54' => 'Argentina',
        '54' => 'Argentina',

        '+55' => 'Brazil',
        '55' => 'Brazil',

        '+56' => 'Chile',
        '56' => 'Chile',

        '+57' => 'Colombia',
        '57' => 'Colombia',

        '+58' => 'Venezuela',
        '58' => 'Venezuela',

        '+60' => 'Malaysia',
        '60' => 'Malaysia',

        '+61' => 'Australia',
        '61' => 'Australia',

        '+62' => 'Indonesia',
        '62' => 'Indonesia',

        '+63' => 'Philippines',
        '63' => 'Philippines',

        '+64' => 'New Zealand',
        '64' => 'New Zealand',

        '+65' => 'Singapore',
        '65' => 'Singapore',

        '+66' => 'Thailand',
        '66' => 'Thailand',

        '+81' => 'Japan',
        '81' => 'Japan',

        '+82' => 'South Korea',
        '82' => 'South Korea',

        '+84' => 'Vietnam',
        '84' => 'Vietnam',

        '+86' => 'China',
        '86' => 'China',

        '+90' => 'Turkey',
        '90' => 'Turkey',

        '+91' => 'India',
        '91' => 'India',
        '091' => 'India',

        '+92' => 'Pakistan',
        '92' => 'Pakistan',

        '+93' => 'Afghanistan',
        '93' => 'Afghanistan',

        '+94' => 'Sri Lanka',
        '94' => 'Sri Lanka',

        '+95' => 'Myanmar',
        '95' => 'Myanmar',

        '+98' => 'Iran',
        '98' => 'Iran',

        '+211' => 'South Sudan',
        '211' => 'South Sudan',

        '+212' => 'Morocco',
        '212' => 'Morocco',

        '+213' => 'Algeria',
        '213' => 'Algeria',

        '+216' => 'Tunisia',
        '216' => 'Tunisia',

        '+218' => 'Libya',
        '218' => 'Libya',

        '+220' => 'Gambia',
        '220' => 'Gambia',

        '+221' => 'Senegal',
        '221' => 'Senegal',

        '+223' => 'Mali',
        '223' => 'Mali',

        '+224' => 'Guinea',
        '224' => 'Guinea',

        '+225' => 'Ivory Coast',
        '225' => 'Ivory Coast',

        '+226' => 'Burkina Faso',
        '226' => 'Burkina Faso',

        '+227' => 'Niger',
        '227' => 'Niger',

        '+228' => 'Togo',
        '228' => 'Togo',

        '+229' => 'Benin',
        '229' => 'Benin',

        '+230' => 'Mauritius',
        '230' => 'Mauritius',

        '+231' => 'Liberia',
        '231' => 'Liberia',

        '+232' => 'Sierra Leone',
        '232' => 'Sierra Leone',

        '+233' => 'Ghana',
        '233' => 'Ghana',

        '+234' => 'Nigeria',
        '234' => 'Nigeria',

        '+235' => 'Chad',
        '235' => 'Chad',

        '+236' => 'Central African Republic',
        '236' => 'Central African Republic',

        '+237' => 'Cameroon',
        '237' => 'Cameroon',

        '+238' => 'Cape Verde',
        '238' => 'Cape Verde',

        '+239' => 'Sao Tome and Principe',
        '239' => 'Sao Tome and Principe',

        '+240' => 'Equatorial Guinea',
        '240' => 'Equatorial Guinea',

        '+241' => 'Gabon',
        '241' => 'Gabon',

        '+242' => 'Congo',
        '242' => 'Congo',

        '+243' => 'DR Congo',
        '243' => 'DR Congo',

        '+244' => 'Angola',
        '244' => 'Angola',

        '+249' => 'Sudan',
        '249' => 'Sudan',

        '+250' => 'Rwanda',
        '250' => 'Rwanda',

        '+251' => 'Ethiopia',
        '251' => 'Ethiopia',

        '+252' => 'Somalia',
        '252' => 'Somalia',

        '+254' => 'Kenya',
        '254' => 'Kenya',

        '+255' => 'Tanzania',
        '255' => 'Tanzania',

        '+256' => 'Uganda',
        '256' => 'Uganda',

        '+257' => 'Burundi',
        '257' => 'Burundi',

        '+260' => 'Zambia',
        '260' => 'Zambia',

        '+263' => 'Zimbabwe',
        '263' => 'Zimbabwe',

        '+264' => 'Namibia',
        '264' => 'Namibia',

        '+265' => 'Malawi',
        '265' => 'Malawi',

        '+266' => 'Lesotho',
        '266' => 'Lesotho',

        '+267' => 'Botswana',
        '267' => 'Botswana',

        '+268' => 'Eswatini',
        '268' => 'Eswatini',

        '+351' => 'Portugal',
        '351' => 'Portugal',

        '+352' => 'Luxembourg',
        '352' => 'Luxembourg',

        '+353' => 'Ireland',
        '353' => 'Ireland',

        '+354' => 'Iceland',
        '354' => 'Iceland',

        '+355' => 'Albania',
        '355' => 'Albania',

        '+356' => 'Malta',
        '356' => 'Malta',

        '+357' => 'Cyprus',
        '357' => 'Cyprus',

        '+358' => 'Finland',
        '358' => 'Finland',

        '+359' => 'Bulgaria',
        '359' => 'Bulgaria',

        '+370' => 'Lithuania',
        '370' => 'Lithuania',

        '+371' => 'Latvia',
        '371' => 'Latvia',

        '+372' => 'Estonia',
        '372' => 'Estonia',

        '+373' => 'Moldova',
        '373' => 'Moldova',

        '+374' => 'Armenia',
        '374' => 'Armenia',

        '+375' => 'Belarus',
        '375' => 'Belarus',

        '+376' => 'Andorra',
        '376' => 'Andorra',

        '+377' => 'Monaco',
        '377' => 'Monaco',

        '+380' => 'Ukraine',
        '380' => 'Ukraine',

        '+381' => 'Serbia',
        '381' => 'Serbia',

        '+382' => 'Montenegro',
        '382' => 'Montenegro',

        '+385' => 'Croatia',
        '385' => 'Croatia',

        '+386' => 'Slovenia',
        '386' => 'Slovenia',

        '+420' => 'Czech Republic',
        '420' => 'Czech Republic',

        '+421' => 'Slovakia',
        '421' => 'Slovakia',

        '+880' => 'Bangladesh',
        '880' => 'Bangladesh',

        '+886' => 'Taiwan',
        '886' => 'Taiwan',

        '+960' => 'Maldives',
        '960' => 'Maldives',

        '+961' => 'Lebanon',
        '961' => 'Lebanon',

        '+962' => 'Jordan',
        '962' => 'Jordan',

        '+963' => 'Syria',
        '963' => 'Syria',

        '+964' => 'Iraq',
        '964' => 'Iraq',

        '+965' => 'Kuwait',
        '965' => 'Kuwait',

        '+966' => 'Saudi Arabia',
        '966' => 'Saudi Arabia',

        '+967' => 'Yemen',
        '967' => 'Yemen',

        '+968' => 'Oman',
        '968' => 'Oman',

        '+970' => 'Palestine',
        '970' => 'Palestine',

        '+971' => 'UAE',
        '971' => 'UAE',

        '+972' => 'Israel',
        '972' => 'Israel',

        '+973' => 'Bahrain',
        '973' => 'Bahrain',

        '+974' => 'Qatar',
        '974' => 'Qatar',

        '+975' => 'Bhutan',
        '975' => 'Bhutan',

        '+976' => 'Mongolia',
        '976' => 'Mongolia',

        '+977' => 'Nepal',
        '977' => 'Nepal',

        '+992' => 'Tajikistan',
        '992' => 'Tajikistan',

        '+993' => 'Turkmenistan',
        '993' => 'Turkmenistan',

        '+994' => 'Azerbaijan',
        '994' => 'Azerbaijan',

        '+995' => 'Georgia',
        '995' => 'Georgia',

        '+996' => 'Kyrgyzstan',
        '996' => 'Kyrgyzstan',

        '+998' => 'Uzbekistan',
        '998' => 'Uzbekistan',
    ];

    // ===============================================
    // Get Users
    // ===============================================
    $allUsersQuery = \App\Models\User::whereNotNull('countrycode');


// ===============================================
// Date Filter
// ===============================================
if ($fromDate && $toDate) {

    $allUsersQuery->whereBetween(
        'created_at',
        [
            \Carbon\Carbon::parse($fromDate)->startOfDay(),
            \Carbon\Carbon::parse($toDate)->endOfDay()
        ]
    );
}


// ===============================================
// Search Filter
// ===============================================
if ($search) {

    $allUsersQuery->where(function ($q) use ($search) {

        $q->where('name', 'LIKE', "%{$search}%")
          ->orWhere('email', 'LIKE', "%{$search}%")
          ->orWhere('mobile_no', 'LIKE', "%{$search}%");

    });
}


// ===============================================
// Country Filter
// ===============================================
if ($countryFilter) {

    $allUsersQuery->where(function ($q) use ($countryFilter) {

        $q->where('countrycode', $countryFilter)
          ->orWhere('countrycode', '+' . $countryFilter);

    });
}

// ===============================================
// Final Users
// ===============================================
$allUsers = $allUsersQuery
    ->latest()
    ->get();

    // ===============================================
    // Group Data
    // ===============================================
    $grouped = [];

    foreach ($allUsers as $user) {

        $code = trim($user->countrycode);

        // Valid Country
        if (isset($countries[$code])) {

            $countryName = $countries[$code];

        } else {

            // Invalid / Unknown Codes
            $countryName = 'Other';
        }

        // Create Array
        if (!isset($grouped[$countryName])) {

            $grouped[$countryName] = [
                'country_name' => $countryName,
                'total' => 0,
                'users' => collect()
            ];
        }

        // Count
        $grouped[$countryName]['total']++;

        // Users List
        $grouped[$countryName]['users']->push($user);
    }

    // ===============================================
    // Final Data
    // ===============================================
    $data = [];

    foreach ($grouped as $item) {

        $data[] = (object)[

            'country_name' => $item['country_name'],

            'total' => $item['total'],

            'users' => $item['users']
        ];
    }

    return view(
        'back-end.reports.analytics-table',
        [
            'type' => 'Country Users',
            'data' => $data
        ]
    );

break;

        /*
        |--------------------------------------------------------------------------
        | SEO Leads
        |--------------------------------------------------------------------------
        */
      case 'seo':
    $fromDate = request('from_date');
    $toDate   = request('to_date');

    // ===============================================
    // Base Query
    // ===============================================
    $seoQuery = \App\Models\Leads::query()
        ->leftJoin('orders', 'leads.order_id', '=', 'orders.order_id')
        ->where('leads.frontendorder', 1);

    // ===============================================
    // Date Filters
    // ===============================================
    if ($fromDate && $toDate) {

    $seoQuery->whereBetween(
        'leads.created_at',
        [
            \Carbon\Carbon::parse($fromDate)->startOfDay(),
            \Carbon\Carbon::parse($toDate)->endOfDay()
        ]
    );
}

    // ===============================================
    // Total Leads
    // ===============================================
    $totalLeads = (clone $seoQuery)
        ->select('leads.*')
        ->latest('leads.created_at')
        ->get();

    // ===============================================
    // Converted Leads
    // ===============================================
    $convertedLeads = (clone $seoQuery)
        ->where('leads.is_converted', 1)
        ->select('leads.*')
        ->latest('leads.created_at')
        ->get();

    // ===============================================
    // Not Converted Leads
    // ===============================================
    $notConvertedLeads = (clone $seoQuery)
        ->where('leads.is_converted', 0)
        ->select('leads.*')
        ->latest('leads.created_at')
        ->get();

    // ===============================================
    // Final Data
    // ===============================================
    $data = [];

    $data[] = (object)[

        // COUNTS
        'total' => $totalLeads->count(),

        'converted' => $convertedLeads->count(),

        'not_converted' => $notConvertedLeads->count(),

        // LISTS
        'total_leads' => $totalLeads,

        'converted_leads' => $convertedLeads,

        'not_converted_leads' => $notConvertedLeads

    ];

    return view(
        'back-end.reports.analytics-table',
        [
            'type' => 'SEO Leads',
            'data' => $data
        ]
    );

        break;


        /*
        |--------------------------------------------------------------------------
        | Lead Sources
        |--------------------------------------------------------------------------
        */
        case 'sources':

            // ===============================================
            // All Leads With Source
            // ===============================================
            // $allLeads = \App\Models\Leads::query()
            //     ->with('source')
            //     ->whereNotNull('lead_source')
            //     ->latest()
            //     ->get();
            $allLeads = \App\Models\Leads::query()
                ->with('source', 'user')
                ->whereNotNull('lead_source');

            if ($fromDate && $toDate) {
                $allLeads->whereBetween('created_at', [
                    \Carbon\Carbon::parse($fromDate)->startOfDay(),
                    \Carbon\Carbon::parse($toDate)->endOfDay()
                ]);
            }

            if ($search) {
                $allLeads->whereHas('user', function ($q) use ($search) {
                    $q->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('email', 'LIKE', "%{$search}%")
                    ->orWhere('mobile_no', 'LIKE', "%{$search}%");
                });
            }

            $allLeads = $allLeads
                ->latest()
                ->get();

            // ===============================================
            // Source Wise Leads
            // ===============================================
            $source106 = $allLeads->filter(function ($lead) {

                return strtolower(optional($lead->source)->source_name) == 'source_106';

            });

            $source1696 = $allLeads->filter(function ($lead) {

                return strtolower(optional($lead->source)->source_name) == 'source_1696';

            });

            $sourceInsta = $allLeads->filter(function ($lead) {

                return strtolower(optional($lead->source)->source_name) == 'source_insta';

            });

            $sourceFacebook = $allLeads->filter(function ($lead) {

                return strtolower(optional($lead->source)->source_name) == 'source_facebook';

            });

            $sourceWhatsapp = $allLeads->filter(function ($lead) {

                return strtolower(optional($lead->source)->source_name) == 'source_whatsapp';

            });

            // ===============================================
            // Final Data
            // ===============================================
            $data = [];

            $data[] = (object)[

                // COUNTS
                'source_106_count' => $source106->count(),

                'source_1696_count' => $source1696->count(),

                'source_insta_count' => $sourceInsta->count(),

                'source_facebook_count' => $sourceFacebook->count(),

                'source_whatsapp_count' => $sourceWhatsapp->count(),

                // LISTS
                'source_106_leads' => $source106,

                'source_1696_leads' => $source1696,

                'source_insta_leads' => $sourceInsta,

                'source_facebook_leads' => $sourceFacebook,

                'source_whatsapp_leads' => $sourceWhatsapp

            ];

            return view(
                'back-end.reports.analytics-table',
                [
                    'type' => 'Lead Sources',
                    'data' => $data
                ]
            );

        default:
            abort(404);
    }
}

public function paperTypeReport(Request $request)
{
    $paperTypes = DB::table('type_papers')
        ->leftJoin('orders', function ($join) {
            $join->on(
                DB::raw('LOWER(TRIM(orders.typeofpaper)) COLLATE utf8mb4_unicode_ci'),
                '=',
                DB::raw('LOWER(TRIM(type_papers.paper_type)) COLLATE utf8mb4_unicode_ci')
            );
        })
        ->select(
            'type_papers.id',
            'type_papers.paper_type',
            DB::raw('COUNT(orders.id) as total_orders')
        )
        ->groupBy('type_papers.id', 'type_papers.paper_type')
        ->orderBy('type_papers.paper_type', 'asc')
        ->get();

    $allCount = $paperTypes->sum('total_orders');

    return view('back-end.reports.paper-type-report', compact('paperTypes', 'allCount'));
}
// public function paperTypeReport(Request $request)
// {
//     $paperTypes = DB::table('type_papers')
//         ->leftJoin('orders', function ($join) {
//             $join->on(
//                 DB::raw('LOWER(TRIM(orders.typeofpaper)) COLLATE utf8mb4_unicode_ci'),
//                 '=',
//                 DB::raw('LOWER(TRIM(type_papers.paper_type)) COLLATE utf8mb4_unicode_ci')
//             );
//         })
//         ->select(
//             'type_papers.id',
//             'type_papers.paper_type',
//             DB::raw('COUNT(orders.id) as total_orders')
//         )
//         ->groupBy('type_papers.id', 'type_papers.paper_type')
//         ->orderBy('type_papers.paper_type', 'asc')
//         ->get();

//     $allCount = $paperTypes->sum('total_orders');

//     return view('back-end.reports.paper-type-report', compact('paperTypes', 'allCount'));
// }                

public function paperTypeOrders(Request $request)
{
    $query = Order::query();

    if ($request->filled('paper_type') && $request->paper_type != 'all') {
        $paperType = DB::table('type_papers')
            ->where('id', $request->paper_type)
            ->first();

        if ($paperType) {
            $query->whereRaw(
                'LOWER(TRIM(typeofpaper)) = ?',
                [strtolower(trim($paperType->paper_type))]
            );
        }
    } else {
        $paperTypeNames = DB::table('type_papers')
            ->pluck('paper_type')
            ->map(fn ($name) => strtolower(trim($name)))
            ->toArray();

        // $query->whereRaw('LOWER(TRIM(typeofpaper)) IN ("' . implode('","', $paperTypeNames) . '")');
        $query->whereIn(DB::raw('LOWER(TRIM(typeofpaper))'),$paperTypeNames);
    }

    if ($request->filled('order')) {
    $search = trim($request->order);

    $query->where(function ($q) use ($search) {
        $q->where('id', $search)
            ->orWhere('order_id', 'like', '%' . $search . '%')
            ->orWhere('title', 'like', '%' . $search . '%');
    });
}

    if ($request->filled('from_date')) {
        $query->whereDate('order_date', '>=', $request->from_date);
    }

    if ($request->filled('to_date')) {
        $query->whereDate('order_date', '<=', $request->to_date);
    }

    $countQuery = DB::table('type_papers')
    ->leftJoin('orders', function ($join) use ($request) {
        $join->on(
            DB::raw('LOWER(TRIM(orders.typeofpaper))'),
            '=',
            DB::raw('LOWER(TRIM(type_papers.paper_type))')
        );

        if ($request->filled('from_date')) {
            $join->whereDate('orders.order_date', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $join->whereDate('orders.order_date', '<=', $request->to_date);
        }

        if ($request->filled('order')) {
            $search = trim($request->order);

            $join->where(function ($q) use ($search) {
                $q->where('orders.id', $search)
                    ->orWhere('orders.order_id', 'like', '%' . $search . '%')
                    ->orWhere('orders.title', 'like', '%' . $search . '%');
            });
        }
    })
    ->select(
        'type_papers.id',
        DB::raw('COUNT(orders.id) as total_orders')
    )
    ->groupBy('type_papers.id')
    ->get();

$paperCounts = [];
$allFilteredCount = 0;

foreach ($countQuery as $count) {
    $paperCounts[$count->id] = $count->total_orders;
    $allFilteredCount += $count->total_orders;
}

    // $orders = $query->orderByDesc('id')->paginate(20);
    $orders = $query->with('user')->orderByDesc('id')->paginate(20);

    if ($orders->isEmpty()) {
        return response()->json([
            'html' => '<tr><td colspan="8" class="text-center text-muted">No orders found.</td></tr>',
            'pagination' => '',
            'counts' => $paperCounts ?? [],
            'all_count' => $allFilteredCount ?? 0
        ]);
    }

    $html = '';

    foreach ($orders as $key => $order) {
        $serial = ($orders->currentPage() - 1) * $orders->perPage() + $key + 1;
        $userHtml = '';

    if ($order->user) {

        $userOrderCount = \App\Models\Order::where('uid', $order->user->id)->count();

        if ($userOrderCount > 10) {
            $class = 'badge-light-success';
            $label = 'Loyal Customer';
        } elseif ($userOrderCount >= 4) {
            $class = 'badge-light-warning';
            $label = 'Repeated';
        } else {
            $class = 'badge-light-info';
            $label = 'Beginner';
        }

        $userHtml = '
            <div class="text-center">
                <div class="fw-bold text-gray-900">'
                    . e($order->user->name) .
                '</div>

                <span class="badge ' . $class . ' fw-bold fs-8 mb-1">'
                    . $label .
                '</span>

                <div>
                    <span class="badge badge-light-danger fs-7 fw-bold">
                        +' . e($order->user->countrycode) . ' ' . e($order->user->mobile_no) . '
                    </span>
                </div>

                <div class="fs-7 fw-bold text-gray-700">'
                    . e($order->user->email) .
                '</div>
            </div>';

    } else {
        $userHtml = '<span class="badge badge-light-danger fs-7 fw-bold">User Was Deleted</span>';
    }

        $html .= '
            <tr>
                <td>' . $serial . '</td>
                <td>' . ($order->order_id ?? 'N/A') . '</td>
                <td>' . $userHtml . '</td>
                <td>' . ($order->typeofpaper ?? 'N/A') . '</td>
                <td>' . ($order->title ?? 'N/A') . '</td>
                <td>' . (!empty($order->order_date) ? date('d M Y', strtotime($order->order_date)) : 'N/A') . '</td>
                <td>' . ($order->amount ?? 'N/A') . '</td>
            </tr>';
    }

    $pagination = '
        <div class="d-flex justify-content-between align-items-center mt-5">
            <div class="text-muted fw-semibold">
                Showing ' . $orders->firstItem() . ' to ' . $orders->lastItem() . ' of ' . $orders->total() . ' records
            </div>

            <div class="d-flex gap-2">';

    if ($orders->onFirstPage()) {
        $pagination .= '<button class="btn btn-sm btn-light" disabled>Previous</button>';
    } else {
        $pagination .= '<button class="btn btn-sm btn-light-primary pagination-btn" data-page="' . ($orders->currentPage() - 1) . '">Previous</button>';
    }

    $pagination .= '<span class="btn btn-sm btn-light active">Page ' . $orders->currentPage() . ' of ' . $orders->lastPage() . '</span>';

    if ($orders->hasMorePages()) {
        $pagination .= '<button class="btn btn-sm btn-light-primary pagination-btn" data-page="' . ($orders->currentPage() + 1) . '">Next</button>';
    } else {
        $pagination .= '<button class="btn btn-sm btn-light" disabled>Next</button>';
    }

    $pagination .= '
            </div>
        </div>';

    return response()->json([
    'html' => $html,
    'pagination' => $pagination,
    'counts' => $paperCounts,
    'all_count' => $allFilteredCount
]);
}

// public function userRetentionReport(Request $request)
// {
//     $year = $request->input('year', now()->year);

//     $latestOrders = DB::table('orders')
//         ->select(
//             'uid',
//             DB::raw('MAX(created_at) as last_order_date'),
//             DB::raw('COUNT(id) as total_orders')
//         )
//         ->whereNotNull('uid')
//         ->where('uid', '!=', 0)
//         ->groupBy('uid');

//     $baseUsers = DB::table('users')
//         ->joinSub($latestOrders, 'latest_orders', function ($join) {
//             $join->on('users.id', '=', 'latest_orders.uid');
//         })
//         ->select(
//             'users.id',
//             'users.name',
//             'users.email',
//             'users.countrycode',
//             'users.mobile_no',
//             'latest_orders.last_order_date',
//             'latest_orders.total_orders'
//         );

//     if ($request->filled('user')) {
//         $search = $request->user;

//         $baseUsers->where(function ($q) use ($search) {
//             $q->where('users.name', 'like', "%$search%")
//                 ->orWhere('users.email', 'like', "%$search%")
//                 ->orWhere('users.mobile_no', 'like', "%$search%");
//         });
//     }
//     $months = [];

//     $currentMonth = now()->year == $year ? now()->month : 12;
//     for ($m = 1; $m <= $currentMonth; $m++) {
//         $monthStart = \Carbon\Carbon::create($year, $m, 1)->startOfMonth();
//         $monthEnd   = \Carbon\Carbon::create($year, $m, 1)->endOfMonth();

//         $retainStart = $monthStart->copy()->subMonths(2)->startOfMonth();
//         $retainEnd   = $monthStart->copy()->subMonth()->endOfMonth();

//         $notRetainStart = $monthStart->copy()->subMonths(9)->startOfMonth();
//         $notRetainEnd   = $monthStart->copy()->subMonths(8)->endOfMonth();

//         $newUsers = DB::table('users')
//         ->leftJoinSub($latestOrders, 'latest_orders', function ($join) {
//             $join->on('users.id', '=', 'latest_orders.uid');
//         })
//         ->select(
//             'users.id',
//             'users.name',
//             'users.email',
//             'users.countrycode',
//             'users.mobile_no',
//             'users.created_at',
//             DB::raw('COALESCE(latest_orders.last_order_date, NULL) as last_order_date'),
//             DB::raw('COALESCE(latest_orders.total_orders, 0) as total_orders')
//         )
//         ->whereDate('users.created_at', '>=', $monthStart)
//         ->whereDate('users.created_at', '<=', $monthEnd)
//         ->where(function ($q) use ($request) {
//             if ($request->filled('user')) {
//                 $search = $request->user;

//                 $q->where('users.name', 'like', '%' . $search . '%')
//                     ->orWhere('users.email', 'like', '%' . $search . '%')
//                     ->orWhere('users.mobile_no', 'like', '%' . $search . '%');
//             }
//         })
//         ->orderByDesc('users.created_at')
//         ->get();

//         $retainUsers = (clone $baseUsers)
//             ->whereBetween('latest_orders.last_order_date', [$retainStart, $retainEnd])
//             ->orderByDesc('latest_orders.last_order_date')
//             ->get();

//         $notRetainUsers = (clone $baseUsers)
//             ->whereBetween('latest_orders.last_order_date', [$notRetainStart, $notRetainEnd])
//             ->orderByDesc('latest_orders.last_order_date')
//             ->get();

//         $months[] = [
//             'month_no' => $m,
//             'month_name' => $monthStart->format('F'),
//             'new_users' => $newUsers,
//             'retain_users' => $retainUsers,
//             'not_retain_users' => $notRetainUsers,
//         ];
//     }

//     return view('back-end.reports.user-retention-report', compact('months', 'year'));
// }

public function userRetentionReport(Request $request)
{
    $year = $request->input('year', now()->year);

    $latestOrders = DB::table('orders')
        ->select(
            'uid',
            DB::raw('MAX(created_at) as last_order_date'),
            DB::raw('COUNT(id) as total_orders')
        )
        ->whereNotNull('uid')
        ->where('uid', '!=', 0)
        ->groupBy('uid');

    $months = [];

    $currentMonth = ($year == now()->year) ? now()->month : 12;
    for ($m = 1; $m <= $currentMonth; $m++) {

        $monthStart = \Carbon\Carbon::create($year, $m, 1)->startOfMonth();
        $monthEnd   = \Carbon\Carbon::create($year, $m, 1)->endOfMonth();

        // Jan users => Apr to Dec retain window
        $retainStart = $monthStart->copy()->addMonths(3)->startOfMonth();
        $retainEnd   = \Carbon\Carbon::create($year, 12, 31)->endOfDay();

        // Jan users => no order till Sep means not retain
        $notRetainEnd = $monthStart->copy()->addMonths(8)->endOfMonth();

        $cohortUsers = DB::table('users')
            ->leftJoinSub($latestOrders, 'latest_orders', function ($join) {
                $join->on('users.id', '=', 'latest_orders.uid');
            })
            ->select(
                'users.id',
                'users.name',
                'users.email',
                'users.countrycode',
                'users.mobile_no',
                'users.created_at',
                DB::raw('COALESCE(latest_orders.last_order_date, NULL) as last_order_date'),
                DB::raw('COALESCE(latest_orders.total_orders, 0) as total_orders')
            )
            ->whereDate('users.created_at', '>=', $monthStart)
            ->whereDate('users.created_at', '<=', $monthEnd);

        if ($request->filled('user')) {
            $search = $request->user;

            $cohortUsers->where(function ($q) use ($search) {
                $q->where('users.name', 'like', '%' . $search . '%')
                    ->orWhere('users.email', 'like', '%' . $search . '%')
                    ->orWhere('users.mobile_no', 'like', '%' . $search . '%');
            });
        }

        $newUsers = (clone $cohortUsers)
            ->orderByDesc('users.created_at')
            ->get();

        $retainUsers = (clone $cohortUsers)
            ->whereExists(function ($q) use ($retainStart, $retainEnd) {
                $q->select(DB::raw(1))
                    ->from('orders')
                    ->whereColumn('orders.uid', 'users.id')
                    ->whereDate('orders.created_at', '>=', $retainStart)
                    ->whereDate('orders.created_at', '<=', $retainEnd);
            })
            ->orderByDesc('users.created_at')
            ->get();

        $notRetainUsers = (clone $cohortUsers)
            ->whereNotNull('latest_orders.last_order_date')
            ->whereNotExists(function ($q) use ($monthEnd, $notRetainEnd) {
                $q->select(DB::raw(1))
                    ->from('orders')
                    ->whereColumn('orders.uid', 'users.id')
                    ->whereDate('orders.created_at', '>', $monthEnd)
                    ->whereDate('orders.created_at', '<=', $notRetainEnd);
            })
            ->orderByDesc('users.created_at')
            ->get();

        $months[] = [
            'month_no' => $m,
            'month_name' => $monthStart->format('F'),
            'new_users' => $newUsers,
            'retain_users' => $retainUsers,
            'not_retain_users' => $notRetainUsers,
        ];
    }

    return view('back-end.reports.user-retention-report', compact('months', 'year'));
}
}
