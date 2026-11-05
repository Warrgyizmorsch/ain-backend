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

    /**
     * Customer Due Payment Report
     * Accessible strictly to Super Admin (1), Admin (4), and Sub Admin (9).
     */
    public function customerDueReport(Request $request)
    {
        if (!auth()->check() || !in_array((int) auth()->user()->role_id, [1, 4, 9])) {
            abort(403, 'Unauthorized access.');
        }

        // Base query: Only role_id 2 (clients/customers), active flag, non-cancelled orders with pending due
        $year = (int) $request->input('year', 2026);

        $query = DB::table('users')
            ->join('orders', 'orders.uid', '=', 'users.id')
            ->where('users.role_id', 2)
            ->where('users.flag', 0)
            ->whereNotIn('orders.projectstatus', ['Cancelled'])
            ->whereRaw('(orders.amount - orders.received_amount) > 0');

        if ($year > 0) {
            $query->whereYear('orders.order_date', $year);
        }

        // Filter by Customer UID (from autocomplete dropdown selection)
        if ($request->filled('uid') && is_numeric($request->uid)) {
            $query->where('users.id', (int) $request->uid);
        } elseif ($request->filled('user') || $request->filled('search')) {
            $search = trim($request->input('user') ?? $request->input('search'));
            if (is_numeric($search)) {
                $query->where(function ($q) use ($search) {
                    $q->where('users.id', (int) $search)
                      ->orWhere('users.mobile_no', 'like', "%{$search}%");
                });
            } else {
                $query->where(function ($q) use ($search) {
                    $q->where('users.name', 'like', "%{$search}%")
                      ->orWhere('users.email', 'like', "%{$search}%")
                      ->orWhere('users.mobile_no', 'like', "%{$search}%");
                    if (stripos($search, 'UKS') !== false) {
                        $q->orWhere('orders.order_id', 'like', "%{$search}%");
                    }
                });
            }
        }

        // Filter by Date Range (orders order_date)
        if ($request->filled('from_date')) {
            $query->whereDate('orders.order_date', '>=', $request->from_date);
        }
        if ($request->filled('to_date')) {
            $query->whereDate('orders.order_date', '<=', $request->to_date);
        }

        // Filter by University / College if provided
        if ($request->filled('college')) {
            $query->where('orders.college_name', 'like', '%' . trim($request->college) . '%');
        }

        $selectQuery = (clone $query)->select(
            'users.id',
            'users.name',
            'users.email',
            'users.countrycode',
            'users.mobile_no',
            DB::raw('COUNT(orders.id) as due_orders_count'),
            DB::raw('SUM(orders.amount) as total_order_amount'),
            DB::raw('SUM(orders.received_amount) as total_received_amount'),
            DB::raw('SUM(orders.amount - orders.received_amount) as total_due_amount'),
            DB::raw('MIN(orders.delivery_date) as nearest_deadline'),
            DB::raw('MAX(orders.delivery_date) as latest_deadline'),
            DB::raw('MAX(orders.order_date) as last_order_date')
        )
        ->groupBy('users.id', 'users.name', 'users.email', 'users.countrycode', 'users.mobile_no')
        ->havingRaw('total_due_amount > 0');

        // Minimum due filter (e.g. >= 100, >= 500)
        if ($request->filled('min_due')) {
            $minDue = (float) $request->min_due;
            $selectQuery->havingRaw('total_due_amount >= ?', [$minDue]);
        }

        // Sorting
        $sortBy = $request->input('sort_by', 'total_due_amount');
        $sortOrder = $request->input('sort_order', 'desc');
        $allowedSorts = ['total_due_amount', 'due_orders_count', 'last_order_date'];
        if (in_array($sortBy, $allowedSorts)) {
            $selectQuery->orderBy($sortBy, $sortOrder === 'asc' ? 'asc' : 'desc');
        } else {
            $selectQuery->orderByDesc('total_due_amount');
        }

        // Summary KPI Metrics (cached for 60s for high performance)
        $cacheKey = 'customer_due_summary_kpi_' . $year;
        $summary = \Illuminate\Support\Facades\Cache::remember($cacheKey, 60, function () use ($year) {
            $aggQuery = DB::table('orders')
                ->join('users', 'users.id', '=', 'orders.uid')
                ->where('users.role_id', 2)
                ->where('users.flag', 0)
                ->whereNotIn('orders.projectstatus', ['Cancelled'])
                ->whereRaw('(orders.amount - orders.received_amount) > 0');

            if ($year > 0) {
                $aggQuery->whereYear('orders.order_date', $year);
            }

            $agg = $aggQuery->selectRaw('
                    COUNT(DISTINCT users.id) as total_customers,
                    COUNT(orders.id) as total_due_orders,
                    SUM(orders.amount - orders.received_amount) as total_due_amount
                ')
                ->first();
            return [
                'total_customers' => (int) ($agg->total_customers ?? 0),
                'total_due_orders' => (int) ($agg->total_due_orders ?? 0),
                'total_due_amount' => (float) ($agg->total_due_amount ?? 0),
            ];
        });

        // CSV export
        if ($request->has('export') && $request->export == 'csv') {
            return $this->exportCustomerDueCsv($selectQuery->get());
        }

        $perPage = (int) $request->input('per_page', 25);
        if (!in_array($perPage, [10, 25, 50, 100])) {
            $perPage = 25;
        }

        $users = $selectQuery->paginate($perPage)->withQueryString();

        return view('back-end.reports.customer-due-report', compact('users', 'summary'));
    }

    /**
     * AJAX endpoint: Get all due orders for a specific customer.
     */
    public function customerDueOrders(Request $request, $userId)
    {
        if (!auth()->check() || !in_array((int) auth()->user()->role_id, [1, 4, 9])) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $year = (int) $request->input('year', 2026);

        $user = DB::table('users')
            ->select('id', 'name', 'email', 'countrycode', 'mobile_no')
            ->where('id', $userId)
            ->first();

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Customer not found.'], 404);
        }

        $ordersQuery = DB::table('orders')
            ->where('uid', $userId)
            ->whereNotIn('projectstatus', ['Cancelled'])
            ->whereRaw('(amount - received_amount) > 0');

        if ($year > 0) {
            $ordersQuery->whereYear('order_date', $year);
        }

        $orders = $ordersQuery->select(
                'id',
                'order_id',
                'title',
                'projectstatus',
                'order_date',
                'delivery_date',
                'delivery_time',
                'amount',
                'received_amount',
                DB::raw('(amount - received_amount) as due_amount')
            )
            ->orderByDesc('delivery_date')
            ->orderByDesc('due_amount')
            ->get();

        return response()->json([
            'success' => true,
            'user' => $user,
            'year' => $year,
            'total_due' => round($orders->sum('due_amount'), 2),
            'orders_count' => $orders->count(),
            'orders' => $orders
        ]);
    }

    /**
     * Stream CSV Export for Customer Due Report
     */
    private function exportCustomerDueCsv($records)
    {
        $filename = 'customer_due_report_' . date('Y-m-d_His') . '.csv';
        $headers = [
            'Content-type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function () use ($records) {
            $file = fopen('php://output', 'w');
            fputs($file, "\xEF\xBB\xBF"); // UTF-8 BOM for Excel compatibility
            fputcsv($file, [
                'Rank',
                'Customer ID',
                'Customer Name',
                'Email',
                'Country Code',
                'Mobile No',
                'Due Orders Count',
                'Total Order Value',
                'Total Received',
                'Net Due Amount',
                'Nearest Deadline',
                'Last Order Date',
            ]);

            foreach ($records as $index => $row) {
                fputcsv($file, [
                    $index + 1,
                    $row->id,
                    $row->name ?? 'N/A',
                    $row->email ?? 'N/A',
                    $row->countrycode ? '+' . ltrim($row->countrycode, '+') : '',
                    $row->mobile_no ?? '',
                    $row->due_orders_count,
                    number_format((float) $row->total_order_amount, 2, '.', ''),
                    number_format((float) $row->total_received_amount, 2, '.', ''),
                    number_format((float) $row->total_due_amount, 2, '.', ''),
                    $row->nearest_deadline ?? 'N/A',
                    $row->last_order_date ?? 'N/A',
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
