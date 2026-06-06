<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportController extends Controller
{

public function followUpReport(Request $request)
{
    $year = $request->input('year', now()->year);

    $months = [];
    $currentMonth = ($year == now()->year) ? now()->month : 12;

    for ($m = 1; $m <= $currentMonth; $m++) {

        $monthStart = Carbon::create($year, $m, 1)->startOfMonth();
        $monthEnd   = Carbon::create($year, $m, 1)->endOfMonth();

        $baseOrders = DB::table('orders')
            ->whereNotNull('uid')
            ->where('uid', '!=', 0)
            ->whereBetween('orders.created_at', [$monthStart, $monthEnd]);

        $totalUsers = (clone $baseOrders)
            ->distinct('uid')
            ->count('uid');

        $followUpUsers = (clone $baseOrders)
            ->whereNotNull('follow_status')
            ->where('follow_status', '!=', '')
            ->distinct('uid')
            ->count('uid');

        $statusCounts = (clone $baseOrders)
            ->whereNotNull('follow_status')
            ->where('follow_status', '!=', '')
            ->selectRaw("
                LOWER(TRIM(follow_status)) as status_name,
                COUNT(DISTINCT uid) as total
            ")
            ->groupBy(DB::raw("LOWER(TRIM(follow_status))"))
            ->pluck('total', 'status_name');

        $selectUserFields = [
            'users.id',
            'users.name',
            'users.email',
            'users.mobile_no',
            'users.countrycode',
            'orders.order_id',
            'orders.follow_status',
            'orders.follow_comment',
            'orders.follow_up_user',
            'orders.followupdate',
        ];

        $totalUsersList = (clone $baseOrders)
            ->join('users', 'users.id', '=', 'orders.uid')
            ->select($selectUserFields)
            ->groupBy(
                'users.id',
                'users.name',
                'users.email',
                'users.mobile_no',
                'users.countrycode',
                'orders.order_id',
                'orders.follow_status',
                'orders.follow_comment',
                'orders.follow_up_user',
                'orders.followupdate'
            )
            ->get();

        $followUpUsersList = (clone $baseOrders)
            ->join('users', 'users.id', '=', 'orders.uid')
            ->whereNotNull('orders.follow_status')
            ->where('orders.follow_status', '!=', '')
            ->select($selectUserFields)
            ->get();

        $negativeButConvincedUsers = (clone $baseOrders)
            ->join('users', 'users.id', '=', 'orders.uid')
            ->whereRaw("LOWER(TRIM(orders.follow_status)) = ?", ['negative but convinced'])
            ->select($selectUserFields)
            ->get();

        $negativeUsers = (clone $baseOrders)
            ->join('users', 'users.id', '=', 'orders.uid')
            ->whereRaw("LOWER(TRIM(orders.follow_status)) = ?", ['negative'])
            ->select($selectUserFields)
            ->get();

        $positiveUsers = (clone $baseOrders)
            ->join('users', 'users.id', '=', 'orders.uid')
            ->whereRaw("LOWER(TRIM(orders.follow_status)) = ?", ['positive'])
            ->select($selectUserFields)
            ->get();

        $positiveReferralUsers = (clone $baseOrders)
            ->join('users', 'users.id', '=', 'orders.uid')
            ->whereRaw("LOWER(TRIM(orders.follow_status)) = ?", ['positive and referral'])
            ->select($selectUserFields)
            ->get();

        $positiveOwnOrderUsers = (clone $baseOrders)
            ->join('users', 'users.id', '=', 'orders.uid')
            ->whereRaw("LOWER(TRIM(orders.follow_status)) = ?", ['positive and own order'])
            ->select($selectUserFields)
            ->get();

        $noResponseUsers = (clone $baseOrders)
            ->join('users', 'users.id', '=', 'orders.uid')
            ->whereRaw("LOWER(TRIM(orders.follow_status)) = ?", ['no response'])
            ->select($selectUserFields)
            ->get();

        $months[] = [
            'month_no' => $m,
            'month_name' => $monthStart->format('F'),

            'total_users' => $totalUsers,
            'followup_users' => $followUpUsers,

            'negative_but_convinced' => $statusCounts['negative but convinced'] ?? 0,
            'negative' => $statusCounts['negative'] ?? 0,
            'positive' => $statusCounts['positive'] ?? 0,
            'positive_referral' => $statusCounts['positive and referral'] ?? 0,
            'positive_own_order' => $statusCounts['positive and own order'] ?? 0,
            'no_response' => $statusCounts['no response'] ?? 0,

            'total_users_list' => $totalUsersList,
            'followup_users_list' => $followUpUsersList,
            'negative_but_convinced_users' => $negativeButConvincedUsers,
            'negative_users' => $negativeUsers,
            'positive_users' => $positiveUsers,
            'positive_referral_users' => $positiveReferralUsers,
            'positive_own_order_users' => $positiveOwnOrderUsers,
            'no_response_users' => $noResponseUsers,
        ];
    }

    return view('back-end.reports.follow-up-report', compact('months', 'year'));
}

public function sourceLeadReport(Request $request)
{
    // 1. Base Leads Query (Date Filters ke sath prepare karein taaki sahi counts milein)
    $baseQuery = \App\Models\Leads::join('sources', 'sources.id', '=', 'leads.lead_source')
        ->where('leads.duplicate_lead', 0)
        ->where('sources.is_delete', 0);

    if ($request->filled('from_date')) {
        $baseQuery->whereDate('leads.created_at', '>=', $request->from_date);
    }
    if ($request->filled('to_date')) {
        $baseQuery->whereDate('leads.created_at', '<=', $request->to_date);
    }

    // Saari filtered leads ek baar me collections me le aate hain counts match karne ke liye
    $allFilteredLeads = clone $baseQuery;
    $leadsCollection = $allFilteredLeads->select('leads.*')->get();

    // 2. Active Sources list map counts ke sath
    $sources = DB::table('sources')
        ->where('is_delete', 0)
        ->get()
        ->map(function($source) use ($leadsCollection) {
            // Har source ke liye filtered collection se matching counts filter karna
            $source->leads_count = $leadsCollection->where('lead_source', $source->id)->count();
            return $source;
        });

    // Total leads count "All Sources" tab ke liye
    $allSourcesCount = $leadsCollection->count();

    // 3. Current active selected filters apply karna final table result ke liye
    if ($request->filled('source_id')) {
        $baseQuery->where('leads.lead_source', $request->source_id);
    }

    // Status Tab filter
    $leadsWithStatus = clone $baseQuery;
    $leadsWithStatusCollection = $leadsWithStatus->select('leads.*')->get();

    $counts = [
        'All'              => $leadsWithStatusCollection->count(),
        'Hot'              => $leadsWithStatusCollection->where('l_status', 'Hot')->count(),
        'Cold'             => $leadsWithStatusCollection->where('l_status', 'Cold')->count(),
        'Warm'             => $leadsWithStatusCollection->where('l_status', 'Warm')->count(),
        'Price'            => $leadsWithStatusCollection->where('l_status', 'Price')->count(),
        'Deadline'         => $leadsWithStatusCollection->where('l_status', 'Deadline')->count(),
        'Serious Concern'  => $leadsWithStatusCollection->where('l_status', 'Serious Concern')->count(),
        'Marks'            => $leadsWithStatusCollection->where('l_status', 'Marks')->count(),
        'Quality'          => $leadsWithStatusCollection->where('l_status', 'Quality')->count(),
        'Customer Service' => $leadsWithStatusCollection->where('l_status', 'Customer Service')->count(),
        'Unknown'          => $leadsWithStatusCollection->where('l_status', 'Unknown')->count(),
    ];

    if ($request->filled('status_tab') && $request->status_tab !== 'All') {
        $baseQuery->where('leads.l_status', $request->status_tab);
    }

    $reports = $baseQuery->select('leads.*')->orderByDesc('leads.created_at')->get();

    return view('back-end.reports.source-lead-report', compact('reports', 'sources', 'counts', 'allSourcesCount'));
}
}
