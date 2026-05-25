<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Sample;
use App\Models\Blog;
use App\Models\Add;
use Illuminate\Support\Facades\Storage;
use App\Models\Order;
use App\Models\User;
use App\Models\Leads;
use App\Models\FeedbackOrder;
use Carbon\Carbon;
use App\Models\Payment;
use Illuminate\Support\Str;
use App\Models\Review;
use App\Models\Experts;
use Illuminate\Support\Facades\Cache;
use App\Services\SchemaService;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    function BreadcrumbList(array $breadcrumbs): string
    {
        $breadcrumbSchema = [
            "@context" => "https://schema.org",
            "@type" => "BreadcrumbList",
            "itemListElement" => []
        ];

        foreach ($breadcrumbs as $position => $breadcrumb) {
            $breadcrumbSchema['itemListElement'][] = [
                "@type" => "ListItem",
                "position" => $position + 1,
                "name" => $breadcrumb['name'],
                "item" => $breadcrumb['url']
            ];
        }

        // Return as a JSON string
        return json_encode($breadcrumbSchema, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    }

    function artical($title, $description, $pdalishDate, $updalishDate)
    {
        return json_encode([
            "@context" => "http://schema.org",
            "@type" => "Article",
            "image" => "https://www.assignnmentinneed.com/assets/media/layout/assignment_logo.webp",


            "mainEntityOfPage" => [
                "@type" => "WebPage",
                "@id" => env('CANONICAL_URL', url()->current()) ?? ""
            ],
            "headline" => $title ?? "",
            "datePublished" => $pdalishDate ?? "",
            "dateModified" => $updalishDate ?? "",
            "author" => [
                "@type" => "Organization",
                "name" => "Assignmenntinneed",
                "url" => "https://www.assignnmentinneed.com",
            ],
            "publisher" => [
                "@type" => "Organization",
                "name" => 'Assignmenntinneed',

            ],
            "description" => $description
        ], JSON_UNESCAPED_SLASHES);
    }

    function generateOrganizationSchema()
    {
        return json_encode([
            "@context" => "https://schema.org",
            "@type" => "Organization",
            "name" => "Assignment In Need",
            "url" => "https://www.assignnmentinneed.com",
            "logo" => "https://www.assignnmentinneed.com/assets/media/avatars/assignment_logo.png",
            "contactPoint" => [
                "@type" => "ContactPoint",
                "telephone" => "+44 7435256433",
                "contactType" => "Customer Service",
                "availableLanguage" => ["English"]
            ],
            "sameAs" => [
                "https://www.facebook.com/assignmentinneed",
                "https://www.instagram.com/p/CXN_t7ALYGH/",
                "https://twitter.com/assignment_in"
            ]
        ]);
    }
    function generateFaqSchema(array $faqEntries): string
    {
        $mainEntity = array_map(fn($entry) => [
            "@type" => "Question",
            "name" => $entry['question'],
            "acceptedAnswer" => [
                "@type" => "Answer",
                "text" => $entry['answer']
            ]
        ], $faqEntries);

        return json_encode([
            "@context" => "https://schema.org",
            "@type" => "FAQPage",
            "mainEntity" => $mainEntity
        ]);
    }

    // public function dashboard(Request $request)
    // {
    //     // chart-2 
    //     $selectedDate = $request->input('selectedDate', null);
    //     $stats = $this->getCountryWiseUsers();

    //     // --- Country Wise Users Logic Start ---
    //     $countryWiseData = DB::table('users')
    //         ->select(\DB::raw("REPLACE(countrycode, '+', '') as code"), \DB::raw("count(*) as total"))
    //         ->whereNotNull('countrycode')
    //         ->where('countrycode', '!=', '')
    //         ->where('role_id', 2) // Sirf Clients ka data dikhane ke liye
    //         ->groupBy('code')
    //         ->get();

    //     $countryMapping = [
    //         '91' => 'India',
    //         '44' => 'UK',
    //         '61' => 'Australia',
    //         '1'  => 'USA/Canada',
    //         '971'=> 'UAE',
    //         '353'=> 'Ireland',
    //         '64' => 'New Zealand',
    //     ];

    //     $countryLabels = [];
    //     $countryCounts = [];

    //     foreach ($countryWiseData as $row) {
    //         $cleanCode = ltrim($row->code, '0'); // leading zeros hatane ke liye
    //         $name = $countryMapping[$cleanCode] ?? "Other (+$cleanCode)";

    //         $countryLabels[] = $name;
    //         $countryCounts[] = $row->total;
    //     }

    //     $countryStats = [
    //         'labels' => $countryLabels,
    //         'data' => $countryCounts
    //     ];
    //     // --- Country Wise Users Logic End ---

    //     // ==========================================
    //     // --- DUE PAYMENTS LOGIC START (NEW) ---
    //     // ==========================================
    //     $dueOrdersList = Order::with(['user', 'payment' => function($query) {
    //     $query->orderBy('id', 'desc');
    //         }])
    //         ->where('uid', '!=', 0)
    //         ->whereRaw('CAST(amount AS SIGNED) > CAST(received_amount AS SIGNED)')
    //         // Sorting by Due Amount (Highest Due First)
    //         ->orderByRaw('(CAST(amount AS SIGNED) - CAST(received_amount AS SIGNED)) DESC')
    //         // Pura data fetch karne ki jagah Pagination apply karein
    //         ->paginate(10); 

    //     $totalDueOrdersCount = Order::where('uid', '!=', 0)
    //         ->whereRaw('CAST(amount AS SIGNED) > CAST(received_amount AS SIGNED)')
    //         ->count();
    //     // ==========================================
    //     // --- DUE PAYMENTS LOGIC END ---
    //     // ==========================================

    //     // ==========================================
    //     // --- SEO LEADS (FRONTEND) GRAPH LOGIC START ---
    //     // ==========================================
    //     $seoFilter = $request->input('seo_filter', 'last_year');
    //     $seoFrom = $request->input('seo_from');
    //     $seoTo = $request->input('seo_to');

    //     // Hum leads table ko orders table se join kar rahe hain taaki current status mil sake
    //     $seoStatsQuery = DB::table('leads')
    //         ->leftJoin('orders', 'leads.order_id', '=', 'orders.order_id')
    //         ->where('leads.frontendorder', 1); // 1 means SEO/Frontend Lead

    //     // Date Filters
    //     if ($seoFilter == 'last_week') {
    //         $seoStatsQuery->where('leads.create_at', '>=', \Carbon\Carbon::now()->subWeek());
    //     } elseif ($seoFilter == 'last_month') {
    //         $seoStatsQuery->where('leads.create_at', '>=', \Carbon\Carbon::now()->subMonth());
    //     } elseif ($seoFilter == 'custom' && $seoFrom && $seoTo) {
    //         $seoStatsQuery->whereBetween('leads.create_at', [$seoFrom, $seoTo]);
    //     } else {
    //         // Default: Last 1 Year
    //         $seoStatsQuery->where('leads.create_at', '>=', \Carbon\Carbon::now()->subYear());
    //     }

    //     $seoData = $seoStatsQuery->select('orders.projectstatus', DB::raw('count(*) as total'))
    //         ->groupBy('orders.projectstatus')
    //         ->get();

    //     // Chart Labels & Default Counts
    //     $seoChartData = [
    //         'Completed' => 0,
    //         'In Progress' => 0,
    //         'Pending' => 0,
    //         'Cancelled' => 0,
    //         'Hold / Other' => 0
    //     ];

    //     $totalSeoLeads = 0;

    //     // Data ko categories mein merge karna
    //     foreach ($seoData as $row) {
    //         $count = $row->total;
    //         $totalSeoLeads += $count;
    //         $status = $row->projectstatus ?? 'Unknown';

    //         if (in_array($status, ['Completed', 'Delivered'])) {
    //             $seoChartData['Completed'] += $count;
    //         } elseif (in_array($status, ['In Progress', 'Draft Ready', 'Draft Delivered'])) {
    //             $seoChartData['In Progress'] += $count;
    //         } elseif (in_array($status, ['Pending', 'Initiated'])) {
    //             $seoChartData['Pending'] += $count;
    //         } elseif ($status == 'Cancelled') {
    //             $seoChartData['Cancelled'] += $count;
    //         } else {
    //             $seoChartData['Hold / Other'] += $count;
    //         }
    //     }
    //     // ==========================================
    //     // --- SEO LEADS GRAPH LOGIC END ---
    //     // ==========================================

    //     // ==========================================
    //     // --- CONVERSION RATIO LOGIC START ---
    //     // ==========================================
    //     $oneYearAgo = \Carbon\Carbon::now()->subYear();

    //     // Pichle 1 saal ki total leads
    //     $totalLeads1Year = DB::table('leads')
    //         ->where('create_at', '>=', $oneYearAgo)
    //         ->count();

    //     // Converted Orders (Jin orders ka paisa aa gaya ho ya status Pending/Cancelled na ho)
    //     $convertedOrders1Year = DB::table('leads')
    //         ->join('orders', 'leads.order_id', '=', 'orders.order_id')
    //         ->where('leads.create_at', '>=', $oneYearAgo)
    //         ->where(function($query) {
    //             $query->where('orders.received_amount', '>', 0)
    //                 ->orWhereNotIn('orders.projectstatus', ['Pending', 'Cancelled', 'Quote', 'Other']);
    //         })
    //         ->count();

    //     // Unconverted Leads
    //     $notConvertedCount = $totalLeads1Year - $convertedOrders1Year;
    //     if ($notConvertedCount < 0) { $notConvertedCount = 0; }

    //     // Percentage nikalne ka formula
    //     $conversionRatio = $totalLeads1Year > 0 
    //         ? round(($convertedOrders1Year / $totalLeads1Year) * 100, 1) 
    //         : 0;
    //     // ==========================================
    //     // --- CONVERSION RATIO LOGIC END ---
    //     // ==========================================

    //     // If no date is selected, use current month and year
    //     if (!$selectedDate) {
    //         $selectedDate = date('Y-m'); // Default to current year and month
    //     }

    //     // Extract selected month and year from the selected date
    //     $currentMonth = date('m', strtotime($selectedDate));
    //     $currentYear = date('Y', strtotime($selectedDate));

    //     $totalOrderCount = Order::whereMonth('order_date', $currentMonth)
    //         ->whereYear('order_date', $currentYear)
    //         ->whereNotNull('admin_id')
    //         ->where('admin_id', '!=', 0)
    //         ->get()
    //         ->groupBy('wid');

    //     $totalOrderChartCount = Order::whereMonth('order_date', $currentMonth)
    //         ->whereYear('order_date', $currentYear)
    //         ->whereNotNull('admin_id')
    //         ->where('admin_id', '!=', 0)
    //         ->count();

    //     $userByWid = [];
    //     $othersOrder = 0; // Initialize a variable to keep track of orders where user name is null or empty

    //     foreach ($totalOrderCount as $wid => $orders) {
    //         // Get the user associated with this 'wid'
    //         $user = User::where('id', $wid)->first();

    //         // Check if user name is not empty or null
    //         if (!empty($user->name)) {
    //             // Store user details along with the orders
    //             $userByWid[] = [
    //                 'user' => $user->name,
    //                 'orders' => $orders->count(),
    //             ];
    //         } else {
    //             // Increment othersOrder count if user name is null or empty
    //             $othersOrder += $orders->count();
    //         }
    //     }

    //     // Include 'Others' order count if there are any orders with null or empty user names
    //     if ($othersOrder > 0) {
    //         $userByWid[] = [
    //             'user' => 'Not Assign',
    //             'orders' => $othersOrder,
    //         ];
    //     }

    //     // Include total orders count
    //     $userByWid[] = [
    //         'user' => 'Total Orders',
    //         'orders' => $totalOrderChartCount,
    //     ];

    //     // chart-3
    //     $selectedDate2 = $request->input('selectedDate', null);

    //     // If no date is selected, use current month and year
    //     if (!$selectedDate2) {
    //         $selectedDate2 = date('Y-m'); // Default to current year and month
    //     }

    //     // Extract selected month and year from the selected date
    //     $currentMonth2 = date('m', strtotime($selectedDate2));
    //     $currentYear2 = date('Y', strtotime($selectedDate2));

    //     $totalOrderCount2 = Order::whereMonth('order_date', $currentMonth2)
    //         ->whereYear('order_date', $currentYear2)
    //         ->where('uid', '!=', 0)
    //         ->get()
    //         ->groupBy('writer_name');

    //     $totalOrderChartCount2 = Order::whereMonth('order_date', $currentMonth2)
    //         ->whereYear('order_date', $currentYear2)
    //         ->where('uid', '!=', 0)
    //         ->count();

    //     $userByWid2 = [];
    //     $othersOrder2 = 0; // Initialize a variable to keep track of orders where user name is null or empty

    //     foreach ($totalOrderCount2 as $writer_name => $orders) {
    //         // Get the user associated with this 'writer_name'
    //         $teamMembers = Order::where('writer_name', $writer_name)->first();

    //         // Check if user name is not empty or null
    //         if (!empty($teamMembers->writer_name)) {
    //             // Store user details along with the orders
    //             $userByWid2[] = [
    //                 'user' => $teamMembers->writer_name,
    //                 'orders' => $orders->count(),
    //             ];
    //         } else {
    //             // Increment othersOrder count if user name is null or empty
    //             $othersOrder2 += $orders->count();
    //         }
    //     }

    //     // Include 'Others' order count if there are any orders with null or empty user names
    //     if ($othersOrder2 > 0) {
    //         $userByWid2[] = [
    //             'user' => 'Not Assign',
    //             'orders' => $othersOrder2,
    //         ];
    //     }

    //     // Include total orders count
    //     $userByWid2[] = [
    //         'user' => 'Total Orders',
    //         'orders' => $totalOrderChartCount2,
    //     ];

    //     // --- Basic Stats ---
    //     $totalOrderCount = Order::count();
    //     $notAssignOrderCount = Order::Where('admin_id', '8392')->where(function ($query)
    //         {
    //         $query->whereNotNull('writer_status')
    //                 ->orWhere('writer_status', '!=', '');
    //         })
    //         ->count();

    //     $inprogressOrder13 = Order::where('writer_status', 'In Progress')->count();
    //     $completeOrder13 = Order::where('writer_status', 'Completed')->count();
    //     $totalwritertl = Order::where('admin_id', '8392')->count();

    //     // Writer
    //     $writerOrder = Order::where('wid', auth()->user()->id)->count();

    //     $writerNotAssignOrderCount = Order::
    //     Where('writer_status', '')
    //     ->where('wid', auth()->user()->id)
    //     ->count();

    //     // Subwriter
    //     $subWriterOrder = Order::where('swid', auth()->user()->id)->count();

    //     // Total User Count
    //     $totalUserCount = User::count();

    //     // Today's Order Count
    //     $todayOrdersCount = Order::where('uid' , '!=' , 0)->whereDate('created_at', Carbon::today())->count();

    //     // Feedback Count
    //     $feedbackCount = Order::where('status_issue', 'Issue Raised')->count();

    //     // Order Count for the Current Month
    //     $currentMonthOrdersCount = Order::whereMonth('created_at', Carbon::now()->month)->count();

    //     // Order by status
    //     $otherOrder = Order::where('projectstatus', 'Other')->count();
    //     $cancelledOrder = Order::where('projectstatus', 'Cancelled')->count();
    //     $pendingOrder = Order::where('projectstatus', 'Pending')->count();
    //     $holdWorkOrder = Order::where('projectstatus', 'Hold Work')->count();
    //     $inprogressOrder = Order::where('projectstatus', 'In Progress')->count();

    //     $userData = User::where('tl_id', auth()->user()->id)->get();
    //     $userData2 = User::where('role_id', 6)->where('flag', 0)->get();
    //     $data = ['Team' => $userData, 'writer' => $userData2];

    //     $Paymentstotal = Payment::count();
    //     $checkedPayments = Payment::where('account_status', 0)->count();
    //     $uncheckedpayment = Payment::where('account_status', 1)->count();
    //     $todaypaymentcheckked = Payment::where('account_status', 1)->where('created_at', Carbon::today())->count();

    //     // --- Chart Data Logic Start ---
    //     $year = date('Y'); 
    //     $newUsersData = [];
    //     $retainedUsersData = [];
    //     $months = [];

    //     for ($m = 1; $m <= 12; $m++) {
    //         $date = \Carbon\Carbon::create($year, $m, 1);
    //         $months[] = $date->format('M/y');

    //         // 1. New Users: Is mahine register hue (Clients only)
    //         $newUsersData[] = \App\Models\User::where('role_id', 2)
    //             ->where('flag', 0) // Aapki table mein flag 0 active users ke liye hai
    //             ->whereMonth('created_at', $m)
    //             ->whereYear('created_at', $year)
    //             ->count();

    //         // 2. Retained Clients: Account purana hai par order is mahine aaya
    //         $retainedUsersData[] = \App\Models\Order::whereMonth('order_date', $m)
    //             ->whereYear('order_date', $year)
    //             ->whereHas('user', function($q) use ($date) {
    //                 $q->where('role_id', 2)
    //                 ->where('created_at', '<', $date->startOfMonth());
    //             })
    //             ->distinct('uid')
    //             ->count();
    //     }

    //     $chartData = [
    //         'labels' => $months,
    //         'newUsers' => $newUsersData,
    //         'retained' => $retainedUsersData
    //     ];

    //     if (auth()->check()) {
    //         switch (auth()->user()->role_id) {
    //             case 1:
    //                 $employees = \App\Models\User::where('role_id', '!=', 2)->get();
    //                 // NEW VARIABLES ADDED TO COMPACT: dueOrdersList, totalDueOrdersCount
    //                 return view('dashboard', compact('userByWid2','userByWid','totalOrderCount', 'totalUserCount', 'todayOrdersCount', 'feedbackCount', 'otherOrder', 'cancelledOrder', 'pendingOrder', 'holdWorkOrder', 'inprogressOrder', 'currentMonthOrdersCount', 'chartData', 'employees','countryStats', 'dueOrdersList', 'totalDueOrdersCount', 'seoChartData', 'totalSeoLeads', 'seoFilter', 'seoFrom', 'seoTo', 'totalLeads1Year', 'convertedOrders1Year', 'notConvertedCount', 'conversionRatio' ));
    //             case 3:
    //                 return view('dashboard.QcTeam');
    //             case 4:
    //                 return view('dashboard.marketingTeam', compact('totalOrderCount', 'totalUserCount', 'todayOrdersCount', 'feedbackCount', 'otherOrder', 'cancelledOrder', 'pendingOrder', 'holdWorkOrder', 'inprogressOrder', 'currentMonthOrdersCount'));
    //             case 5:
    //                 return view('dashboard.projectTeam');
    //             case 6:
    //                 return view('dashboard.writer', compact('writerOrder', 'writerNotAssignOrderCount', 'data'));
    //             case 7:
    //                 return view('dashboard.subWriter', compact('subWriterOrder'));
    //             case 8:
    //                 return view('dashboard.team13', compact('totalwritertl', 'inprogressOrder13', 'completeOrder13', 'data', 'notAssignOrderCount', 'todayOrdersCount', 'feedbackCount', 'otherOrder', 'cancelledOrder', 'pendingOrder', 'holdWorkOrder', 'inprogressOrder', 'currentMonthOrdersCount'));
    //             case 9:
    //                 return view('dashboard.marketingTeam', compact('totalOrderCount', 'totalUserCount', 'todayOrdersCount', 'feedbackCount', 'otherOrder', 'cancelledOrder', 'pendingOrder', 'holdWorkOrder', 'inprogressOrder', 'currentMonthOrdersCount'));
    //             case 10:
    //                 return view('dashboard.marketingTeam', compact('totalOrderCount', 'totalUserCount', 'todayOrdersCount', 'feedbackCount', 'otherOrder', 'cancelledOrder', 'pendingOrder', 'holdWorkOrder', 'inprogressOrder', 'currentMonthOrdersCount'));
    //              case 11:
    //                 return view('dashboard.payments', compact('Paymentstotal', 'checkedPayments', 'uncheckedpayment', 'todaypaymentcheckked'));
    //                  case 12:
    //                 return view('dashboard.payments', compact('Paymentstotal', 'checkedPayments', 'uncheckedpayment', 'todaypaymentcheckked'));

    //             default:
    //                 abort(403, 'Unauthorized action.');
    //         }
    //     } else {
    //         abort(403, 'Unauthorized action.');
    //     }
    // }

    public function dashboard(Request $request)
    {
        // chart-2 
        $selectedDate = $request->input('selectedDate', null);
        $stats = $this->getCountryWiseUsers();

        // --- Country Wise Users Logic Start ---
        // --- Country Wise Users Logic Start ---
        $countryWiseData = DB::table('users')
            ->select(
                DB::raw("countrycode as code"),
                DB::raw("count(*) as total")
            )
            ->whereNotNull('countrycode')
            ->where('countrycode', '!=', '')

            ->groupBy('countrycode')
            ->get();

        $countryMapping = [
            '1' => 'USA/Canada',
            '44' => 'UK',
            '61' => 'Australia',
            '64' => 'New Zealand',
            '91' => 'India',
            '971' => 'UAE',
            '353' => 'Ireland',
            '359' => 'Bulgaria',
            '92' => 'Pakistan',
            '880' => 'Bangladesh',
            '977' => 'Nepal',
            '94' => 'Sri Lanka',
            '234' => 'Nigeria',
            '233' => 'Ghana',
            '27' => 'South Africa',
            '966' => 'Saudi Arabia',
            '974' => 'Qatar',
            '965' => 'Kuwait',
            '968' => 'Oman',
            '973' => 'Bahrain',
        ];

        $countryFinalData = [];
        $otherTotal = 0;

        foreach ($countryWiseData as $row) {
            $cleanCode = preg_replace('/[^0-9]/', '', $row->code);
            $cleanCode = ltrim($cleanCode, '0');

            if ($cleanCode && isset($countryMapping[$cleanCode])) {
                $countryName = $countryMapping[$cleanCode];

                if (!isset($countryFinalData[$countryName])) {
                    $countryFinalData[$countryName] = 0;
                }

                $countryFinalData[$countryName] += $row->total;
            } else {
                $otherTotal += $row->total;
            }
        }

        if ($otherTotal > 0) {
            $countryFinalData['Other'] = $otherTotal;
        }

        arsort($countryFinalData);

        $countryStats = [
            'labels' => array_keys($countryFinalData),
            'data' => array_values($countryFinalData),
        ];
        // --- Country Wise Users Logic End ---

       // ==========================================
        // --- DUE PAYMENTS LOGIC START (OPTIMIZED) ---
        // ==========================================
        $dueOrdersList = Order::with([
            'user',
            'payment' => function ($query) {
                $query->orderBy('id', 'desc');
            }
        ])
            ->where('uid', '!=', 0)
            
            // OPTIMIZATION 1: whereDate ki jagah simple 'where' use kiya. 
            // Isse database Date column par laga Index use karega aur result milliseconds me dega.
            ->where('order_date', '>=', '2026-01-01 00:00:00')
            
            ->whereRaw('CAST(amount AS SIGNED) > CAST(received_amount AS SIGNED)')
            // Sorting by Due Amount (Highest Due First)
            ->orderByRaw('(CAST(amount AS SIGNED) - CAST(received_amount AS SIGNED)) DESC')
            // Pagination apply karein
            ->paginate(10);

        // OPTIMIZATION 2: Alag se Count query chalane ki jagah Laravel Pagination ka in-built total() function use kiya. 
        // Isse 1 puri Database Query ka time bach gaya!
        $totalDueOrdersCount = $dueOrdersList->total();

        // dd([
        //     'Message' => 'Testing',
        //     'Total_Pending_Orders_After_2025' => $totalDueOrdersCount
        // ]);
        // ==========================================
        // --- DUE PAYMENTS LOGIC END ---
        // ==========================================

        // ==========================================
        // --- SEO LEADS (FRONTEND) GRAPH LOGIC START ---
        // ==========================================
         // =========================
    // FILTER VALUES
    // =========================
            $seoFilter = request()->input('seo_filter', 'last_year');
            $seoFrom   = request()->input('seo_from');
            $seoTo     = request()->input('seo_to');

            // =========================
            // SEO LEADS QUERY
            // frontendorder = 1  => SEO Lead
            // is_converted = 1   => Converted
            // =========================
            $seoStatsQuery = DB::table('leads')
                ->where('frontendorder', 1);

            // =========================
            // DATE FILTERS
            // =========================
            if ($seoFilter == 'last_week') {

                $seoStatsQuery->whereDate(
                    'create_at',
                    '>=',
                    Carbon::now()->subWeek()
                );

            } elseif ($seoFilter == 'last_month') {

                $seoStatsQuery->whereDate(
                    'create_at',
                    '>=',
                    Carbon::now()->subMonth()
                );

            } elseif ($seoFilter == 'custom' && $seoFrom && $seoTo) {

                $seoStatsQuery->whereBetween(
                    'create_at',
                    [
                        Carbon::parse($seoFrom)->startOfDay(),
                        Carbon::parse($seoTo)->endOfDay()
                    ]
                );

            } else {

                // Default => Last 1 Year
                $seoStatsQuery->whereDate(
                    'create_at',
                    '>=',
                    Carbon::now()->subYear()
                );
            }

            // =========================
            // TOTAL SEO LEADS
            // =========================
            $totalSeoLeads = (clone $seoStatsQuery)->count();

            // =========================
            // CONVERTED LEADS
            // =========================
            $convertedLeads = (clone $seoStatsQuery)
                ->where('is_converted', 1)
                ->count();

            // =========================
            // NOT CONVERTED LEADS
            // =========================
            $notConvertedLeads = (clone $seoStatsQuery)
                ->where(function ($q) {
                    $q->where('is_converted', 0)
                    ->orWhereNull('is_converted');
                })
                ->count();

            // =========================
            // CHART DATA
            // =========================
            $seoChartData = [
                'Converted' => $convertedLeads,
                'Not Converted' => $notConvertedLeads,
            ];
        // ==========================================
        // --- SEO LEADS GRAPH LOGIC END ---
        // ==========================================

        // ==========================================
        // --- LEAD SOURCES GRAPH LOGIC START (NEW) ---
        // ==========================================
        $leadSourcesCount = DB::table('leads')
            ->join('sources', 'leads.lead_source', '=', 'sources.id')
            ->whereIn('sources.source_name', [
                'source_106', 
                'source_1696', 
                'source_insta', 
                'source_facebook', 
                'source_whatsapp'
            ])
            ->select('sources.source_name', DB::raw('COUNT(leads.id) as count'))
            ->groupBy('sources.source_name')
            ->pluck('count', 'source_name')
            ->toArray();

        // Data ko aapas me map kar rahe hain
        $sourceData = [
            'Source 106'      => $leadSourcesCount['source_106'] ?? 0,
            'Source 1696'     => $leadSourcesCount['source_1696'] ?? 0,
            'Source insta'    => $leadSourcesCount['source_insta'] ?? 0,
            'Source facebook' => $leadSourcesCount['source_facebook'] ?? 0,
            'Source whatsapp' => $leadSourcesCount['source_whatsapp'] ?? 0,
        ];

        $totalSourceLeads = array_sum($sourceData);
        // ==========================================
        // --- LEAD SOURCES GRAPH LOGIC END ---
        // ==========================================

        // ==========================================
        // --- CONVERSION RATIO LOGIC START ---
        // ==========================================
        $from = request('from');
        $to   = request('to');

        if ($from && $to) {
            $currentMonthStart = Carbon::parse($from)->startOfDay();
            $currentMonthEnd   = Carbon::parse($to)->endOfDay();
        } else {
            $currentMonthStart = Carbon::now()->startOfMonth();
            $currentMonthEnd   = Carbon::now()->endOfMonth();
        }

        $orderQuery = Order::query();
        $orderQuery->where('uid', '!=', 0);
        $orderQuery->whereBetween('order_date', [$currentMonthStart,$currentMonthEnd]);
        $convertedOrders1Year = $orderQuery->count();

        $totalLeads1Year = DB::table('leads')->whereBetween('create_at', [$currentMonthStart,$currentMonthEnd])->count();
        $notConvertedCount = max($totalLeads1Year - $convertedOrders1Year, 0);
        $conversionRatio = $totalLeads1Year > 0 ? round(($convertedOrders1Year / $totalLeads1Year) * 100, 1): 0;
        // ==========================================
        // --- CONVERSION RATIO LOGIC END ---
        // ==========================================

        // If no date is selected, use current month and year
        if (!$selectedDate) {
            $selectedDate = date('Y-m'); // Default to current year and month
        }

        // Extract selected month and year from the selected date
        $currentMonth = date('m', strtotime($selectedDate));
        $currentYear = date('Y', strtotime($selectedDate));

        $totalOrderCount = Order::whereMonth('order_date', $currentMonth)
            ->whereYear('order_date', $currentYear)
            ->whereNotNull('admin_id')
            ->where('admin_id', '!=', 0)
            ->get()
            ->groupBy('wid');

        $totalOrderChartCount = Order::whereMonth('order_date', $currentMonth)
            ->whereYear('order_date', $currentYear)
            ->whereNotNull('admin_id')
            ->where('admin_id', '!=', 0)
            ->count();

        $userByWid = [];
        $othersOrder = 0; // Initialize a variable to keep track of orders where user name is null or empty

        foreach ($totalOrderCount as $wid => $orders) {
            // Get the user associated with this 'wid'
            $user = User::where('id', $wid)->first();

            // Check if user name is not empty or null
            if (!empty($user->name)) {
                // Store user details along with the orders
                $userByWid[] = [
                    'user' => $user->name,
                    'orders' => $orders->count(),
                ];
            } else {
                // Increment othersOrder count if user name is null or empty
                $othersOrder += $orders->count();
            }
        }

        // Include 'Others' order count if there are any orders with null or empty user names
        if ($othersOrder > 0) {
            $userByWid[] = [
                'user' => 'Not Assign',
                'orders' => $othersOrder,
            ];
        }

        // Include total orders count
        $userByWid[] = [
            'user' => 'Total Orders',
            'orders' => $totalOrderChartCount,
        ];

        // chart-3
        $selectedDate2 = $request->input('selectedDate', null);

        // If no date is selected, use current month and year
        if (!$selectedDate2) {
            $selectedDate2 = date('Y-m'); // Default to current year and month
        }

        // Extract selected month and year from the selected date
        $currentMonth2 = date('m', strtotime($selectedDate2));
        $currentYear2 = date('Y', strtotime($selectedDate2));

        $totalOrderCount2 = Order::whereMonth('order_date', $currentMonth2)
            ->whereYear('order_date', $currentYear2)
            ->where('uid', '!=', 0)
            ->get()
            ->groupBy('writer_name');

        $totalOrderChartCount2 = Order::whereMonth('order_date', $currentMonth2)
            ->whereYear('order_date', $currentYear2)
            ->where('uid', '!=', 0)
            ->count();

        $userByWid2 = [];
        $othersOrder2 = 0; // Initialize a variable to keep track of orders where user name is null or empty

        foreach ($totalOrderCount2 as $writer_name => $orders) {
            // Get the user associated with this 'writer_name'
            $teamMembers = Order::where('writer_name', $writer_name)->first();

            // Check if user name is not empty or null
            if (!empty($teamMembers->writer_name)) {
                // Store user details along with the orders
                $userByWid2[] = [
                    'user' => $teamMembers->writer_name,
                    'orders' => $orders->count(),
                ];
            } else {
                // Increment othersOrder count if user name is null or empty
                $othersOrder2 += $orders->count();
            }
        }

        // Include 'Others' order count if there are any orders with null or empty user names
        if ($othersOrder2 > 0) {
            $userByWid2[] = [
                'user' => 'Not Assign',
                'orders' => $othersOrder2,
            ];
        }

        // Include total orders count
        $userByWid2[] = [
            'user' => 'Total Orders',
            'orders' => $totalOrderChartCount2,
        ];

        // --- Basic Stats ---
        $totalOrderCount = Order::count();
        $notAssignOrderCount = Order::Where('admin_id', '8392')->where(function ($query) {
            $query->whereNotNull('writer_status')
                ->orWhere('writer_status', '!=', '');
        })
            ->count();

        $inprogressOrder13 = Order::where('writer_status', 'In Progress')->count();
        $completeOrder13 = Order::where('writer_status', 'Completed')->count();
        $totalwritertl = Order::where('admin_id', '8392')->count();

        // Writer
        $writerOrder = Order::where('wid', auth()->user()->id)->count();

        $writerNotAssignOrderCount = Order::
            Where('writer_status', '')
            ->where('wid', auth()->user()->id)
            ->count();

        // Subwriter
        $subWriterOrder = Order::where('swid', auth()->user()->id)->count();

        // Total User Count
        $totalUserCount = User::count();

        // Today's Order Count
        $todayOrdersCount = Order::where('uid', '!=', 0)->whereDate('created_at', Carbon::today())->count();

        // Feedback Count
        $feedbackCount = Order::where('status_issue', 'Issue Raised')->count();

        // Order Count for the Current Month
        $currentMonthOrdersCount = Order::whereMonth('created_at', Carbon::now()->month)->count();

        // Order by status
        $otherOrder = Order::where('projectstatus', 'Other')->count();
        $cancelledOrder = Order::where('projectstatus', 'Cancelled')->count();
        $pendingOrder = Order::where('projectstatus', 'Pending')->count();
        $holdWorkOrder = Order::where('projectstatus', 'Hold Work')->count();
        $inprogressOrder = Order::where('projectstatus', 'In Progress')->count();

        $userData = User::where('tl_id', auth()->user()->id)->get();
        $userData2 = User::where('role_id', 6)->where('flag', 0)->get();
        $data = ['Team' => $userData, 'writer' => $userData2];

        $Paymentstotal = Payment::count();
        $checkedPayments = Payment::where('account_status', 0)->count();
        $uncheckedpayment = Payment::where('account_status', 1)->count();
        $todaypaymentcheckked = Payment::where('account_status', 1)->where('created_at', Carbon::today())->count();

        // --- Chart Data Logic Start ---
        $year = date('Y');

        $months = [];
        $newUsersData = [];
        $retainedUsersData = [];
        $loyalUsersData = [];

        for ($m = 1; $m <= 12; $m++) {

            $monthStart = \Carbon\Carbon::create($year, $m, 1)->startOfMonth();
            $monthEnd   = \Carbon\Carbon::create($year, $m, 1)->endOfMonth();

            $months[] = $monthStart->format('M/y');

            $fromDate = $monthStart->format('Y-m-d') . ' 00:00:00';
            $toDate   = $monthEnd->format('Y-m-d') . ' 23:59:59';

            $threeMonthsAgo = date('Y-m-d H:i:s', strtotime('-3 months', strtotime($fromDate)));

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

            // Converted users
            $convertedNewUserIds = User::whereIn('id', $convertedUserIds)
                ->whereBetween('created_at', [$fromDate, $toDate])
                ->pluck('id');

            $convertedOldUserIds = $convertedUserIds->diff($convertedNewUserIds);

            $convertedLoyalUserIds = $convertedOldUserIds
                ->intersect($allLoyalUserIds);

            $convertedRetainUserIds = User::whereIn('id', $convertedOldUserIds)
                ->where('created_at', '<', $threeMonthsAgo)
                ->whereNotIn('id', $convertedLoyalUserIds)
                ->pluck('id');

            $convertedRegularUsers = $convertedOldUserIds
                ->diff($convertedLoyalUserIds)
                ->diff($convertedRetainUserIds)
                ->count();

            // Not converted users
            $notConvertedNewUserIds = User::whereIn('id', $notConvertedOnlyUserIds)
                ->whereBetween('created_at', [$fromDate, $toDate])
                ->pluck('id');

            $notConvertedOldUserIds = $notConvertedOnlyUserIds
                ->diff($notConvertedNewUserIds);

            $notConvertedLoyalUserIds = $notConvertedOldUserIds
                ->intersect($allLoyalUserIds);

            $notConvertedRetainUserIds = User::whereIn('id', $notConvertedOldUserIds)
                ->where('created_at', '<', $threeMonthsAgo)
                ->whereNotIn('id', $notConvertedLoyalUserIds)
                ->pluck('id');

            $notConvertedRegularUsers = $notConvertedOldUserIds
                ->diff($notConvertedLoyalUserIds)
                ->diff($notConvertedRetainUserIds)
                ->count();

            // Final chart values same as User Report
            $newUsersData[] =
                $convertedNewUserIds->count()
                + $notConvertedNewUserIds->count();

            $retainedUsersData[] =
                $convertedRetainUserIds->count()
                + $convertedRegularUsers
                + $notConvertedRetainUserIds->count()
                + $notConvertedRegularUsers;

            $loyalUsersData[] =
                $convertedLoyalUserIds->count()
                + $notConvertedLoyalUserIds->count();
        }

        $chartData = [
            'labels' => $months,
            'newUsers' => $newUsersData,
            'retained' => $retainedUsersData,
            'loyal' => $loyalUsersData
        ];
        // --- Chart Data Logic End ---

        if (auth()->check()) {
            switch (auth()->user()->role_id) {
                case 1:
                    $employees = \App\Models\User::where('role_id', '!=', 2)->get();
                    // NEW VARIABLES ADDED TO COMPACT: sourceData, totalSourceLeads
                    return view('dashboard', compact('userByWid2', 'userByWid', 'totalOrderCount', 'totalUserCount', 'todayOrdersCount', 'feedbackCount', 'otherOrder', 'cancelledOrder', 'pendingOrder', 'holdWorkOrder', 'inprogressOrder', 'currentMonthOrdersCount', 'chartData', 'employees', 'countryStats', 'dueOrdersList', 'totalDueOrdersCount', 'seoChartData', 'totalSeoLeads', 'seoFilter', 'seoFrom', 'seoTo', 'totalLeads1Year', 'convertedOrders1Year', 'notConvertedCount', 'conversionRatio', 'sourceData', 'totalSourceLeads'));
                case 3:
                    return view('dashboard.QcTeam');
                case 4:
                    return view('dashboard.marketingTeam', compact('totalOrderCount', 'totalUserCount', 'todayOrdersCount', 'feedbackCount', 'otherOrder', 'cancelledOrder', 'pendingOrder', 'holdWorkOrder', 'inprogressOrder', 'currentMonthOrdersCount'));
                case 5:
                    return view('dashboard.projectTeam');
                case 6:
                    return view('dashboard.writer', compact('writerOrder', 'writerNotAssignOrderCount', 'data'));
                case 7:
                    return view('dashboard.subWriter', compact('subWriterOrder'));
                case 8:
                    return view('dashboard.team13', compact('totalwritertl', 'inprogressOrder13', 'completeOrder13', 'data', 'notAssignOrderCount', 'todayOrdersCount', 'feedbackCount', 'otherOrder', 'cancelledOrder', 'pendingOrder', 'holdWorkOrder', 'inprogressOrder', 'currentMonthOrdersCount'));
                case 9:
                    return view('dashboard.marketingTeam', compact('totalOrderCount', 'totalUserCount', 'todayOrdersCount', 'feedbackCount', 'otherOrder', 'cancelledOrder', 'pendingOrder', 'holdWorkOrder', 'inprogressOrder', 'currentMonthOrdersCount'));
                case 10:
                    return view('dashboard.marketingTeam', compact('totalOrderCount', 'totalUserCount', 'todayOrdersCount', 'feedbackCount', 'otherOrder', 'cancelledOrder', 'pendingOrder', 'holdWorkOrder', 'inprogressOrder', 'currentMonthOrdersCount'));
                case 11:
                    return view('dashboard.payments', compact('Paymentstotal', 'checkedPayments', 'uncheckedpayment', 'todaypaymentcheckked'));
                case 12:
                    return view('dashboard.payments', compact('Paymentstotal', 'checkedPayments', 'uncheckedpayment', 'todaypaymentcheckked'));

                default:
                    abort(403, 'Unauthorized action.');
            }
        } else {
            abort(403, 'Unauthorized action.');
        }
    }

    // public function conversionRatioData(Request $request)
// {
//     $filter = $request->input('filter', 'current_month');
//     $from = $request->input('from');
//     $to = $request->input('to');

//     $query = DB::table('leads');

//     if ($filter == 'last_month') {
//         $startDate = \Carbon\Carbon::now()->subMonth()->startOfMonth();
//         $endDate = \Carbon\Carbon::now()->subMonth()->endOfMonth();
//     } elseif ($filter == 'last_week') {
//         $startDate = \Carbon\Carbon::now()->subWeek()->startOfWeek();
//         $endDate = \Carbon\Carbon::now()->subWeek()->endOfWeek();
//     } elseif ($filter == 'custom' && $from && $to) {
//         $startDate = \Carbon\Carbon::parse($from)->startOfDay();
//         $endDate = \Carbon\Carbon::parse($to)->endOfDay();
//     } else {
//         $startDate = \Carbon\Carbon::now()->startOfMonth();
//         $endDate = \Carbon\Carbon::now()->endOfMonth();
//     }

//     $totalLeads = (clone $query)
//         ->whereBetween('create_at', [$startDate, $endDate])
//         ->count();

//     $convertedLeads = DB::table('leads')
//         ->whereBetween('create_at', [$startDate, $endDate])
//         ->where('is_converted', 1)
//         ->count();

//     $notConvertedLeads = DB::table('leads')
//         ->whereBetween('create_at', [$startDate, $endDate])
//         ->where('is_converted', 0)
//         ->count();

//     $conversionRatio = $totalLeads > 0
//         ? round(($convertedLeads / $totalLeads) * 100, 1)
//         : 0;

//     return response()->json([
//         'total_leads' => $totalLeads,
//         'converted_leads' => $convertedLeads,
//         'not_converted_leads' => $notConvertedLeads,
//         'conversion_ratio' => $conversionRatio,
//     ]);
// }

    public function conversionRatioData(Request $request)
    {
        $filter = $request->input('filter', 'current_month');
        $from = $request->input('from');
        $to = $request->input('to');

        if ($filter == 'last_month') {
            $startDate = \Carbon\Carbon::now()->subMonth()->startOfMonth();
            $endDate = \Carbon\Carbon::now()->subMonth()->endOfMonth();
        } elseif ($filter == 'last_week') {
            $startDate = \Carbon\Carbon::now()->subWeek()->startOfWeek();
            $endDate = \Carbon\Carbon::now()->subWeek()->endOfWeek();
        } elseif ($filter == 'custom' && $from && $to) {
            $startDate = \Carbon\Carbon::parse($from)->startOfDay();
            $endDate = \Carbon\Carbon::parse($to)->endOfDay();
        } else {
            $startDate = \Carbon\Carbon::now()->startOfMonth();
            $endDate = \Carbon\Carbon::now()->endOfMonth();
        }

        $totalLeads = DB::table('leads')
            ->whereBetween('create_at', [$startDate, $endDate])
            ->count();

        $convertedLeads = \App\Models\Order::query()
            ->where('uid', '!=', 0)
            ->whereBetween('order_date', [$startDate, $endDate])
            ->count();

        $notConvertedLeads = max($totalLeads - $convertedLeads, 0);

        $conversionRatio = $totalLeads > 0
            ? round(($convertedLeads / $totalLeads) * 100, 1)
            : 0;

        return response()->json([
            'total_leads' => $convertedLeads + $notConvertedLeads,
            'converted_leads' => $convertedLeads,
            'not_converted_leads' => $notConvertedLeads,
            'conversion_ratio' => $conversionRatio,
        ]);
    }

    // country wise user count
    public function getCountryWiseUsers()
    {
        // 1. DUNIYA BHAR KI COUNTRIES KI MASTER LIST
        $globalCountries = [
            'India' => ['+91', '91', '091'],
            'USA/Canada' => ['+1', '1'],
            'UK' => ['+44', '44'],
            'Australia' => ['+61', '61'],
            'UAE' => ['+971', '971'],
            'Pakistan' => ['+92', '92'],
            'Bangladesh' => ['+880', '880'],
            'Sri Lanka' => ['+94', '94'],
            'Nepal' => ['+977', '977'],
            'Azerbaijan' => ['+994', '994'],
            'Romania' => ['+40', '40'],
            'New Zealand' => ['+64', '64'],
            'Ghana' => ['+233', '233'],
            'Denmark' => ['+45', '45'],
            'Germany' => ['+49', '49'],
            'Nigeria' => ['+234', '234'],
            'Saudi Arabia' => ['+966', '966'],
            'South Africa' => ['+27', '27'],
            'Singapore' => ['+65', '65'],
            'Malaysia' => ['+60', '60'],
            'Ireland' => ['+353', '353'],
            'Oman' => ['+968', '968'],
            'Qatar' => ['+974', '974'],
            'Kuwait' => ['+965', '965'],
            'Bahrain' => ['+973', '973'],
            'France' => ['+33', '33'],
            'Italy' => ['+39', '39'],
            'Spain' => ['+34', '34'],
            'Netherlands' => ['+31', '31'],
            'Sweden' => ['+46', '46'],
            'Switzerland' => ['+41', '41'],
            'China' => ['+86', '86'],
            'Japan' => ['+81', '81'],
            'South Korea' => ['+82', '82'],
            'Russia' => ['+7', '7'],
            'Brazil' => ['+55', '55'],
            'Mexico' => ['+52', '52'],
            'Egypt' => ['+20', '20'],
            'Kenya' => ['+254', '254'],
            'Uganda' => ['+256', '256'],
            'Turkey' => ['+90', '90'],
            'Iran' => ['+98', '98'],
            'Ukraine' => ['+380', '380'],
            'Bulgaria' => ['+359', '359'],
            'Guinea' => ['+224', '224'],
            'Malta' => ['+356', '356'],
            'Liberia' => ['+231', '231'],
            'Mauritius' => ['+230', '230'],
            'Greece' => ['+30', '30'],
            'Austria' => ['+43', '43'],
            'Iraq' => ['+964', '964'],
            'Cameroon' => ['+237', '237'],
            'Albania' => ['+355', '355'],
            'Belgium' => ['+32', '32'],
            'Israel' => ['+972', '972'],
            'Zambia' => ['+260', '260'],
            'Latvia' => ['+371', '371']
        ];

        // 2. Fetch required columns directly (Super fast process)
        $users = \Illuminate\Support\Facades\DB::table('users')
            ->where('flag', 0)
            ->select('countrycode', 'mobile_no')
            ->get();

        $knownCountriesCount = [];
        $totalOthers = 0; // Jinka kuch bhi match nahi hoga, sab isi me plus honge

        // 3. Match CountryCode OR Mobile No Prefix
        foreach ($users as $user) {
            $matched = false;
            $uCode = trim($user->countrycode);
            $uMobile = trim($user->mobile_no);

            foreach ($globalCountries as $countryName => $codes) {

                // Condition A: Kya 'countrycode' exactly list me hai?
                if (in_array($uCode, $codes)) {
                    $knownCountriesCount[$countryName] = ($knownCountriesCount[$countryName] ?? 0) + 1;
                    $matched = true;
                    break;
                }

                // Condition B: Agar countrycode match nahi hua, to mobile_no check karo
                if (!$matched && !empty($uMobile)) {
                    foreach ($codes as $code) {
                        if (str_starts_with($uMobile, $code)) {
                            $knownCountriesCount[$countryName] = ($knownCountriesCount[$countryName] ?? 0) + 1;
                            $matched = true;
                            break 2;
                        }
                    }
                }
            }

            // Condition C: Agar na countrycode mila aur na mobile_no start hua, to Other me dalo
            if (!$matched) {
                $totalOthers++;
            }
        }

        arsort($knownCountriesCount);

        $finalStats = [];
        foreach ($knownCountriesCount as $country => $count) {
            $finalStats["$country ($count)"] = $count;
        }

        // Yahan dhyan dein: Agar ghalat code h, to sirf 1 baar add hoga "Other / Unknown" naam se
        if ($totalOthers > 0) {
            $finalStats["Other / Unknown ($totalOthers)"] = $totalOthers;
        }

        return [
            'labels' => array_keys($finalStats),
            'data' => array_values($finalStats)
        ];
    }

    public function write_advertisement()
    {
        // Retrieve all advertisements from the database
        $advertisements = Add::all();

        // Pass the advertisements to the view
        return view('blog-sample.advertisement', compact('advertisements'));
    }
    public function advertisement_store(Request $request)
    {
        // Create a new instance of the Add model
        $add = new Add();

        // Set the created_by field to the name of the authenticated user
        $add->created_by = auth()->user()->name;

        if ($request->hasFile('photo')) {
            $uploadedFile = $request->file('photo');

            // Generate a unique filename based on the original file name
            $fileName = uniqid() . '_' . $uploadedFile->getClientOriginalName();

            // Define the destination path
            $destinationPath = public_path('assets/media/blogthumbnail');

            // Move the uploaded file to the destination path
            $uploadedFile->move($destinationPath, $fileName);

            // Update the user's photo field with the file path
            $add->Images = 'assets/media/blogthumbnail/' . $fileName;
        } else {
            // If no photo is uploaded, set a default value
            $add->Images = 'assets/media/avatars/demo.png';
        }

        // Save the advertisement
        $add->save();

        // Redirect back with success message
        return redirect()->back()->with('success', 'Add submitted successfully');
    }
    public function destroyAdvertisement($id)
    {
        // Logic to delete the blog entry
        $add = Add::find($id);
        $add->delete();

        // Redirect or respond as needed
        return redirect()->back()->with('success', 'entry deleted successfully');
    }

    public function blog(Request $request)
    {
        $blogs = Blog::where('type', 'blog')
            ->orderByDesc('id') // Ordering by 'dest' column in descending order
            ->paginate(5);

        $data['title'] = 'Assignment In Need- Assignment Writing Help Services Blogs';
        $data['description'] = 'Read insightful blogs on assignment writing help services at Assignment In Need. Discover tips, expert advice, and guidance to help you excel in your academic work.';
        $data['keyword'] = '';

        if ($request->has('page')) {
            $data['canonicalPage'] = 'https://www.assignnmentinneed.com/blog?page=' . $request->input('page');
        } else {
            $data['canonical'] = 'https://www.assignnmentinneed.com/blog'; // Adjust this route name as per your routes file
        }

        return view('frontend.header.blog', compact('blogs', 'data'));
    }
    public function write_blog()
    {
         $authors = DB::table('author')
        ->orderBy('name', 'asc')
        ->get();
        return view('blog-sample.blog', compact('authors'));
    }
    public function blog_list(Request $request)
    {
        // Retrieve the filter parameters from the request
        $title = $request->input('title');
        $type = $request->input('type');

        // Initialize the query builder for the Blog model
        $query = Blog::query();

        // Apply the title filter if provided
        if ($title) {
            $query->where('tittle', 'like', '%' . $title . '%');
        }

        // Apply the type filter if provided
        if ($type) {
            $query->where('type', $type);
        }

        // Order the results by creation date and paginate
        $data['blog'] = $query->orderBy('created_at', 'desc')->paginate(20);

        // Pass the filtered blog list to the view
        return view("blog-sample.blog-list", compact('data'));
    }

    public function blogEdit(Request $request, $id)
    {
        $data = [
            'blog' => Blog::find($id)
        ];

        // Decode FAQ data if it exists, otherwise set it as an empty array
        $faqData = !empty($data['blog']->faq) ? json_decode($data['blog']->faq, true) : [];
        $authors = DB::table('author')
        ->orderBy('name', 'asc')
        ->get();

        return view("blog-sample.blog-edit", compact('data', 'faqData', 'authors'));
    }


    public function getBlogBySlug($slug)
    {
        // $data['blog'] = Blog::find($id);

        $data['blog'] = Blog::where('slug', $slug)->first();
        $data['recent_post'] = Blog::where('type', 'blog')->latest()->take(5)->get();

        // Check if the blog exists
        if (!$data['blog']) {
            abort(404); // Or handle the case where the blog with the given slug is not found
        }


        $data['title'] = $data['blog']->meta_title;
        $data['description'] = $data['blog']->meta_discribtion;
        $data['keyword'] = $data['blog']->meta_tag;
        $data['canonical'] = 'https://www.assignnmentinneed.com/blog/' . $data['blog']->slug;
        if ($data['blog']->faq) {

            $faqs = json_decode($data['blog']->faq, true);
            $data['Faqschema'] = $this->generateFaqSchema($faqs);


        } else {
            $data['Faqschema'] = $data['blog']->schema;
        }
        $canonical = 'https://www.assignnmentinneed.com/uk/' . $data['blog']->slug;


        $data['artical'] = $this->Artical($data['blog']->tittle, $data['description'], $data['blog']->created_at, $data['blog']->updated_at);
        $data['org'] = $this->generateOrganizationSchema();

        $data['BreadcrumbList'] = $this->BreadcrumbList([
            ['name' => 'Home', 'url' => 'https://www.assignmentinneed.com/'],
            ['name' => 'Assignment In Need- Assignment Writing Help Services Blogs', 'url' => 'https://www.assignmentinneed.com/blog'],
            ['name' => $data['blog']->tittle, 'url' => 'https://www.assignmentinneed.com/blog/' . $slug]
        ]);




        return view("frontend.header.blog-detail", compact('data'));
    }

    public function blog_store(Request $request)
    {
        if ($request->input('type') === "blog") {
            // Validate the request data
            $request->validate([
                'blogTitle' => 'required',
                'blogContent' => 'required',
                'MetaTag' => 'required',
                'Metadescription' => 'required',

            ]);

            // Check if the title already exists
            $existingBlog = Blog::where('tittle', $request->input('blogTitle'))->first();
            if ($existingBlog) {
                return redirect()->back()->with('error', 'Blog with this title already exists.');
            }

            // Generate a unique slug for the blog title
            $slug = Str::slug($request->input('blogTitle'), '-');
            $existingSlugCount = Blog::where('slug', $slug)->count();
            if ($existingSlugCount > 0) {
                $slug .= '-' . ($existingSlugCount + 1);
            }

            $blog = new Blog;
            $blogContent = $request['blogContent'];

            // Process Base64 images in <img> tags within the content
            if (strpos($blogContent, '<img') !== false) {
                preg_match_all('/<img[^>]+src="([^">]+)"/', $blogContent, $matches);

                foreach ($matches[1] as $imageSrc) {
                    // Check if the image source is Base64
                    if (preg_match('/^data:image\/(\w+);base64,/', $imageSrc, $base64Matches)) {
                        $imageType = $base64Matches[1]; // Extract image type (e.g., png, jpeg)
                        $base64Data = substr($imageSrc, strpos($imageSrc, ',') + 1); // Remove metadata
                        $decodedImage = base64_decode($base64Data);

                        if ($decodedImage !== false) {
                            $destinationPath = base_path('blogs/blog-content-images/');
                            if (!file_exists($destinationPath)) {
                                mkdir($destinationPath, 0755, true);
                            }

                            // Use the blog title as the image name, sanitized and with the appropriate extension
                            $fileName = Str::slug($request->input('blogTitle'), '-') . '.' . $imageType;
                            $fullPath = $destinationPath . '/' . $fileName;

                            file_put_contents($fullPath, $decodedImage); // Save the decoded image

                            // Replace the Base64 image source with the file path in the blog content
                            $relativePath = 'blogs/blog-content-images/' . $fileName;
                            $blogContent = str_replace($imageSrc, $relativePath, $blogContent);
                        }
                    }
                }
            }

            // Set blog properties
            $blog->tittle = $request['blogTitle'];
            $blog->content = $blogContent;
            $blog->type = $request['type'];
            $blog->slug = $slug;
            $blog->FAQ = $request['faq_data'];
            $blog->meta_title = $request['MetaTag'];
            $blog->meta_discribtion = $request['Metadescription'];
            $blog->author_id = $request['author_id'];

            // Handle main photo upload
            if ($request->hasFile('photo')) {
                $uploadedFile = $request->file('photo');

                // Use the blog title as the image name (if title is too long, truncate it for safety)
                $fileExtension = $uploadedFile->getClientOriginalExtension();
                $fileName = $slug . '.' . $fileExtension;
                $destinationPath = base_path('assets/media/blogthumbnail');

                // Ensure the directory exists
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0755, true);
                }

                // Move the uploaded file to the destination path
                $uploadedFile->move($destinationPath, $fileName);

                // Save the relative path in the database
                $blog->Images = 'assets/media/blogthumbnail/' . $fileName;
            } else {
                // Set default image if no photo is uploaded
                $blog->Images = 'assets/media/avatars/demo.png';
            }

            // Save the blog entry
            $blog->save();
            return redirect()->back()->with('success', 'Blog submitted successfully');
        }
    }




    public function blog_edit(Request $request, $id)
    {
        // dd( $request);

        $blog = Blog::find($id);
        if (!$blog) {
            return redirect()->back()->with('error', 'Blog not found');
        }



        $blogContent = $request->input('blogContent');

        // Process Base64 images in <img> tags within the content
        if (strpos($blogContent, '<img') !== false) {
            preg_match_all('/<img[^>]+src="([^">]+)"/', $blogContent, $matches);

            foreach ($matches[1] as $imageSrc) {
                // Check if the image source is Base64
                if (preg_match('/^data:image\/(\w+);base64,/', $imageSrc, $base64Matches)) {
                    $imageType = $base64Matches[1]; // Extract image type (e.g., png, jpeg)
                    $base64Data = substr($imageSrc, strpos($imageSrc, ',') + 1); // Remove metadata
                    $decodedImage = base64_decode($base64Data);

                    if ($decodedImage !== false) {
                        $destinationPath = base_path('blogs/blog-content-images/');
                        if (!file_exists($destinationPath)) {
                            mkdir($destinationPath, 0755, true);
                        }

                        // Use the blog title as the image name, sanitized and with the appropriate extension
                        $fileName = Str::slug($request->input('blogTitle'), '-') . '.' . $imageType;
                        $fullPath = $destinationPath . '/' . $fileName;

                        file_put_contents($fullPath, $decodedImage); // Save the decoded image

                        // Replace the Base64 image source with the file path in the blog content
                        $relativePath = 'blogs/blog-content-images/' . $fileName;
                        $blogContent = str_replace($imageSrc, $relativePath, $blogContent);
                    }
                }
            }
        }

        // Update the blog attributes
        $blog->tittle = $request->input('blogTitle');
        $blog->slug = Str::slug($request->input('blogTitle'), '-');
        $blog->content = $blogContent;
        $blog->FAQ = $request->input('faq_data');
        $blog->meta_title = $request->input('MetaTag');
        $blog->meta_discribtion = $request->input('Metadescription');
        $blog->author_id = $request->input('author_id');
        // dd($blog->meta_title );
        // Handle main photo upload if provided
        if ($request->hasFile('photo')) {
            $uploadedFile = $request->file('photo');
            $extension = $uploadedFile->getClientOriginalExtension(); // Get file extension
            $fileName = $blog->slug . '.' . $extension; // Use slug as filename
            $destinationPath = base_path('assets/media/blogthumbnail');
            $fullPath = $destinationPath . '/' . $fileName;

            // Ensure the directory exists
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }

            // Delete existing image with the same slug name
            if (file_exists($fullPath)) {
                unlink($fullPath);
            }

            // Save the new uploaded photo
            $uploadedFile->move($destinationPath, $fileName);

            // Save image path in the database
            $blog->Images = 'assets/media/blogthumbnail/' . $fileName;
        }

        // Save the updated blog entry
        $blog->save();

        return redirect()->back()->with('success', 'Blog updated successfully');
    }




    public function destroyBlog($id)
    {
        // Logic to delete the blog entry
        $blog = Blog::find($id);
        $blog->delete();

        // Redirect or respond as needed
        return redirect()->back()->with('success', 'Blog entry deleted successfully');
    }

    public function sample()
    {
        $samples = Blog::where('type', 'sample')->paginate(3);
        $data['title'] = 'Free Assignment and Homework and More Sample- Assignment In Need';
        $data['description'] = 'Free samples For Assignments, essays, research papers, homework, and dissertations on Assignment In Need. Support we provide to help you achieve academic success.';
        $data['keyword'] = '';
        $data['canonical'] = 'https://www.assignnmentinneed.com/samples';
        return view('frontend/header/samples', compact('samples', 'data'));
        // return view('frontend/header/samples');
    }
    public function sample_store(Request $request)
    {
        if ($request->input('type') === "sample") {
            // Validate the request data
            $request->validate([
                'blogTitle' => 'required',
                'blogContent' => 'required',
            ]);

            // Check if the title already exists
            $existingBlog = Blog::where('tittle', $request->input('blogTitle'))->first();
            if ($existingBlog) {
                return redirect()->back()->with('error', 'Sample with this title already exists.');
            }

            // Generate slug
            $slug = Str::slug($request->input('blogTitle'), '-');

            // Check if the slug is unique
            $existingSlugCount = Blog::where('slug', $slug)->count();
            if ($existingSlugCount > 0) {
                // If the slug already exists, append a unique identifier
                $slug .= '-' . ($existingSlugCount + 1);
            }

            $blog = new Blog;
            $blogContent = $request['blogContent'];
            $images = [];

            // Check if the blog content contains any <img> tags
            if (strpos($blogContent, '<img') !== false) {
                // Use regular expression to extract image URLs from <img> tags
                preg_match_all('/<img[^>]+src="([^">]+)"/', $blogContent, $matches);

                // Iterate through each matched image URL
                foreach ($matches[1] as $image) {
                    // Extract image URL
                    $imageUrl = $image;

                    // Generate a unique filename based on the image URL
                    $fileName = basename($imageUrl); // Extracts the filename from the URL

                    // Download image from URL
                    $imageContents = file_get_contents($imageUrl);

                    // Check if image download was successful
                    if ($imageContents !== false) {
                        // Define the destination directory where the image will be saved
                        $destinationPath = 'assets/media/blogthumbnail';

                        // Save the image to the storage disk
                        $saved = public_path('assets/media/blogthumbnail');

                        // Check if image was successfully saved
                        if ($saved) {
                            // Construct the public URL for the saved image
                            $publicUrl = public_path('assets/media/blogthumbnail');

                            // Store the public URL for later use
                            $images[] = $publicUrl;
                        } else {
                            // Handle error if image could not be saved
                            // (e.g., log the error, display a message to the user)
                        }
                    } else {
                        // Handle error if image could not be downloaded
                        // (e.g., log the error, display a message to the user)
                    }
                }
            }
            $blog->tittle = $request['blogTitle'];
            $blog->content = $request['blogContent'];
            $blog->type = $request['type'];

            $blog->slug = Str::slug($request->input('blogTitle'), '-');

            if ($request->hasFile('photo')) {
                $uploadedFile = $request->file('photo');

                // Generate a unique filename based on the original file name
                $fileName = uniqid() . '_' . $uploadedFile->getClientOriginalName();

                // Define the destination path
                $destinationPath = public_path('assets/media/blogthumbnail');

                // Move the uploaded file to the destination path
                $uploadedFile->move($destinationPath, $fileName);

                // Update the user's photo field with the file path
                $blog->Images = 'assets/media/blogthumbnail/' . $fileName;
            } else {
                // If no photo is uploaded, set a default value
                $blog->Images = 'assets/media/avatars/demo.png';
            }

            // echo '<pre>'; print_r($blog); exit;

            $blog->save();
            return redirect()->back()->with('success', 'Sample submitted successfully');


        }

    }
    public function getSampleBySlug($slug)
    {
        // $data['blog'] = Blog::find($id);
        $data['blog'] = Blog::where('slug', $slug)->first();
        // Check if the blog exists
        if (!$data['blog']) {
            abort(404); // Or handle the case where the blog with the given slug is not found
        }

        $data['recent_post'] = Blog::where('type', 'sample')->latest()->take(5)->get();
        $data['title'] = $slug;
        $data['description'] = '';
        $data['keyword'] = '';



        return view("frontend.header.sample-detail", compact('data'));
    }
    public function create_sample()
    {

        return view('blog-sample.sample');
    }
    public function sample_list()
    {

        $data = [
            'blog' => Blog::where('type', 'sample')->orderBy('created_at', 'desc')->get(),
        ];

        return view("blog-sample.sample-list", compact('data'));
    }
    public function destroySample($id)
    {
        // Logic to delete the blog entry
        $blog = Blog::find($id);
        $blog->delete();

        // Redirect or respond as needed
        return redirect()->back()->with('success', 'Sample entry deleted successfully');
    }
    public function pricing()
    {
        $data['title'] = 'Affordable Prices For All Assignment Help 40% Off & Free CV- Assignment In Need';
        $data['description'] = 'Assignment In Need offers affordable prices for all assignment help services, ensuring that you get professional academic assistance without breaking the bank.';
        $data['keyword'] = '';
        return view("frontend.pricing", compact('data'));
    }
    // public function placeOrder()
    // {
    //     $data['title'] = 'Place Your Order for Assignment Help Easy & Secure at 40% off-Assignment In Need';
    //     $data['description'] = 'Easily place your order for top-quality assignment help with Assignment In Need. Enjoy a seamless, secure process and get expert assistance tailored to your needs. Start now!';
    //     $data['keyword'] = '';
    //     return view("frontend.place-order", compact('data'));
    // }

    public function thankyou()
    {
        return view('thankyou');
    }

    public function review()
    {
        $data['review'] = Review::orderBy('created_at', 'desc')->take(9)->get(); // Load only first 9
        $data['expert'] = Experts::distinct('service', 'subject') // Avoid repeated subjects
            ->take(10) // Limit to 10 experts
            ->get();
        $data['title'] = 'Read Uk Students Reviews About Us - Assignment In Need';
        $data['description'] = 'Read genuine student reviews about our assignment writing help uk. Discover why learners from London, Manchester, Glasgow & Cardiff trust Assignment In Need';

        return view('review.review', compact('data'));
    }

    public function loadMoreReviews(Request $request)
    {
        $offset = $request->input('offset', 0);
        $reviews = Review::orderBy('created_at', 'desc')->skip($offset)->take(90)->get();
        return response()->json(['reviews' => $reviews]);
    }

    public function review_create()
    {

        return view('review.create-review');
    }
    public function store(Request $request)
    {


        $review = new Review();
        $review->name = $request['name'];
        $review->deadline = $request['deadline'];
        $review->submission_date = $request['submission_date'];
        $review->services_type = $request['services_type'];
        $review->location = $request['location'];
        $review->customer_rating = $request['customer_rating'];
        $review->description = $request['description'];
        $review->review_reply = $request['review_reply'];

        $review->save();

        return redirect()->back()->with('success', 'Review submitted successfully!');
    }

    public function update(Request $request, $id)
    {

        $review = Review::findOrFail($id);
        $review->name = $request['name'];
        $review->deadline = $request['deadline'];
        $review->submission_date = $request['submission_date'];
        $review->services_type = $request['services_type'];
        $review->location = $request['location'];
        $review->customer_rating = $request['customer_rating'];
        $review->description = $request['description'];
        $review->review_reply = $request['review_reply'];
        $review->save();

        return redirect('/review-list')->with('success', 'Review updated successfully!');
    }

    public function review_list()
    {
        $data['review'] = Review::orderBy('created_at', 'desc')->get(); // Load only first 9
        return view('review.review_list', compact('data'));
    }

    public function review_edit(Request $request, $id)
    {
        $review = Review::find($id);
        return view('review.create-review', compact('review'));
    }

    public function destroy($id)
    {
        $review = Review::findOrFail($id); // or however your model is named
        $review->delete();

        return redirect()->back()->with('success', 'Review deleted successfully!');
    }

    public function pillar()
    {
        // Extract slug from the request URL
        $slug = request()->segment(1);

        // Cache the meta and schema data for 60 minutes
        $cached = Cache::remember("pillar_{$slug}_meta_data", 60, function () use ($slug) {
            $schemaService = new SchemaService();
            $pillarConfig = config("dataload.pillar_pages.{$slug}");

            // Validate configuration exists
            if (!$pillarConfig) {
                abort(404, 'Pillar page not found');
            }

            return [
                'title' => $pillarConfig['meta']['title'] ?? '',
                'description' => $pillarConfig['meta']['description'] ?? '',
                'canonical' => $pillarConfig['meta']['canonical'] ?? '',
                'keyword' => $pillarConfig['meta']['keyword'] ?? '',
                'website' => $schemaService->generateWebsiteSchema(),
                'org' => $schemaService->generateOrganizationSchema(),
                'artical' => $schemaService->generateArticleSchema(
                    $pillarConfig['article']['headline'] ?? '',
                    $pillarConfig['article']['description'] ?? '',
                    $pillarConfig['article']['image'] ?? '',
                    $pillarConfig['article']['url'] ?? ''
                ),
                'Faqschema' => $schemaService->generateFaqSchema($pillarConfig['faqs'] ?? []),
                'flag' => $schemaService->hrefFlagUrl(), // Include hreflang tags if needed
            ];
        });

        // Pass data to the corresponding view
        $view = "pillar-pages.{$slug}";
        $data = $cached;

        // Verify view exists
        if (!view()->exists($view)) {
            abort(404, 'Pillar page view not found');
        }

        return view($view, compact('data'));
    }

    public function currentBreak()
    {
        $break = DB::table('user_break_times')
            ->where('user_id', auth()->id())
            ->whereNull('end_time')
            ->latest('id')
            ->first();

        return response()->json([
            'active' => !empty($break),
            'break_type' => $break->break_type ?? null,
        ]);
    }

    public function startBreak(Request $request)
    {
        DB::table('user_break_times')->insert([
            'user_id' => auth()->id(),
            'break_type' => $request->break_type,
            'start_time' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json(['status' => true]);
    }

    public function endBreak(Request $request)
    {
        $break = DB::table('user_break_times')
            ->where('user_id', auth()->id())
            ->where('break_type', $request->break_type)
            ->whereNull('end_time')
            ->latest('id')
            ->first();

        if ($break) {
            $seconds = now()->diffInSeconds(\Carbon\Carbon::parse($break->start_time));

            DB::table('user_break_times')
                ->where('id', $break->id)
                ->update([
                    'end_time' => now(),
                    'total_seconds' => $seconds,
                    'updated_at' => now(),
                ]);
        }

        return response()->json(['status' => true]);
    }

    public function breakTimeReport(Request $request)
    {
        $allowedSubAdminId = 13715;

        if (
            auth()->user()->role_id != 1 &&
            !(auth()->user()->role_id == 9 && auth()->id() == $allowedSubAdminId)
        ) {
            abort(403, 'Unauthorized access.');
        }
        $query = DB::table('user_break_times')
            ->leftJoin('users', 'users.id', '=', 'user_break_times.user_id')
            ->select(
                'user_break_times.*',
                'users.name as employee_name'
            )
            ->whereNotNull('user_break_times.end_time');

        if ($request->filled('employee')) {
            $query->where('users.name', 'like', '%' . $request->employee . '%');
        }

        if ($request->filled('from_date')) {
            $query->whereDate('user_break_times.start_time', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('user_break_times.start_time', '<=', $request->to_date);
        }

        $reports = $query
            ->orderByDesc('user_break_times.start_time')
            ->get();

        return view('back-end.reports.break-time-report', compact('reports'));
    }

}
