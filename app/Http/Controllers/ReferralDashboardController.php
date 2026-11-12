<?php

namespace App\Http\Controllers;

use App\Models\Leads;
use App\Models\Order;
use App\Models\User;
use App\Models\WalletTransaction;
use App\Services\ReferralCodeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReferralDashboardController extends Controller
{
    public const VALID_TABS = ['clients', 'orders', 'signups', 'executives', 'wallet'];

    protected function authorizeSuperAdmin(): void
    {
        if (! Auth::check() || (int) Auth::user()->role_id !== 1) {
            abort(403, 'Unauthorized. Only Super Admin can access the Referral System dashboard.');
        }
    }

    public function index(Request $request)
    {
        $this->authorizeSuperAdmin();

        $activeTab = (string) $request->input('tab', 'clients');
        if (! in_array($activeTab, self::VALID_TABS, true)) {
            $activeTab = 'clients';
        }

        $filters = $this->extractFilters($request);
        $schemaReady = $this->isReferralSchemaReady();

        $summary = $this->buildSummaryStats($schemaReady);
        $executivesList = User::where('flag', 0)
            ->whereIn('role_id', ReferralCodeService::STAFF_EXECUTIVE_ROLE_IDS)
            ->orderBy('name')
            ->get(['id', 'name', 'role_id', 'referral_prefix']);

        $filteredReferrer = null;
        if ($filters['referrer_id']) {
            $filteredReferrer = User::select('id', 'name', 'referral_code', 'referral_prefix')
                ->find($filters['referrer_id']);
        }

        $tabCounts = $this->buildTabCounts($filters, $schemaReady, $activeTab);

        $perPage = (int) $request->input('per_page', 25);
        if (! in_array($perPage, [10, 25, 50, 100], true)) {
            $perPage = 25;
        }

        $clients = null;
        $orders = null;
        $signups = null;
        $executives = collect();
        $walletTransactions = null;
        $orderStatuses = [];
        $signupStatuses = [];

        if ($activeTab === 'clients') {
            $clientsQuery = $this->withClientsAggregates(
                $this->applyClientsQuery($filters, $schemaReady),
                $schemaReady
            );

            $clients = $this->sortClientsQuery($clientsQuery, $schemaReady)
                ->paginate($perPage)
                ->withQueryString();
        } elseif ($activeTab === 'orders') {
            if ($schemaReady['orders']) {
                $orders = $this->applyOrdersQuery($filters)
                    ->with([
                        'user:id,name,email,countrycode,mobile_no,refer_id,referral_code',
                        'referredByUser:id,name,email,referral_code,referral_discount_percent,referral_prefix,referral_executive_id',
                        'referredByUser.referralExecutive:id,name,referral_prefix',
                        'lead:id,order_id,is_converted',
                    ])
                    ->orderByDesc('orders.id')
                    ->paginate($perPage)
                    ->withQueryString();

                $orderStatuses = $this->resolveOrderStatusesForPage($orders->items());
            } else {
                $orders = new \Illuminate\Pagination\LengthAwarePaginator([], 0, $perPage);
            }
        } elseif ($activeTab === 'signups') {
            $signups = $this->withSignupsAggregates(
                $this->applySignupsQuery($filters, $schemaReady),
                $schemaReady
            )
                ->orderByDesc('users.id')
                ->paginate($perPage)
                ->withQueryString();

            $signupStatuses = $this->resolveSignupStatuses($signups->items());
        } elseif ($activeTab === 'executives') {
            $executives = $this->buildExecutivePerformance($filters, $schemaReady);
        } elseif ($activeTab === 'wallet') {
            $walletTransactions = $this->applyWalletQuery($filters)
                ->with([
                    'user:id,name,email,referral_code,Wallet,total_referral_earnings,referral_executive_id,referral_prefix',
                    'user.referralExecutive:id,name,referral_prefix',
                ])
                ->orderByDesc('id')
                ->paginate($perPage)
                ->withQueryString();
        }

        return view('back-end.referral.index', compact(
            'activeTab',
            'filters',
            'schemaReady',
            'summary',
            'executivesList',
            'filteredReferrer',
            'tabCounts',
            'perPage',
            'clients',
            'orders',
            'signups',
            'executives',
            'walletTransactions',
            'orderStatuses',
            'signupStatuses'
        ));
    }

    public function export(Request $request): StreamedResponse
    {
        $this->authorizeSuperAdmin();

        $activeTab = (string) $request->input('tab', 'clients');
        if (! in_array($activeTab, self::VALID_TABS, true)) {
            $activeTab = 'clients';
        }

        $filters = $this->extractFilters($request);
        $schemaReady = $this->isReferralSchemaReady();
        $fileName = 'referral-' . $activeTab . '-' . now()->format('Ymd-His') . '.csv';

        return response()->streamDownload(function () use ($activeTab, $filters, $schemaReady) {
            $handle = fopen('php://output', 'w');
            if (! $handle) {
                return;
            }

            // UTF-8 BOM for Excel compatibility
            fwrite($handle, "\xEF\xBB\xBF");

            if ($activeTab === 'clients') {
                fputcsv($handle, [
                    'Client ID',
                    'Client Name',
                    'Email',
                    'Phone',
                    'Referral Code',
                    'Discount %',
                    'Executive',
                    'Prefix',
                    'Generated At',
                    'Referred Signups',
                    'Referred Orders',
                    'Bonus Orders',
                    'Referred Revenue (GBP)',
                    'Discount Given (GBP)',
                    'Total Referral Earnings (GBP)',
                    'Current Wallet (GBP)',
                    'Last Updated By',
                ]);

                $rowsQuery = $this->withClientsAggregates(
                    $this->applyClientsQuery($filters, $schemaReady),
                    $schemaReady
                );
                $rows = $this->sortClientsQuery($rowsQuery, $schemaReady)->get();

                foreach ($rows as $row) {
                    $walletVal = is_numeric($row->Wallet) ? (float) $row->Wallet : 0.0;
                    fputcsv($handle, array_map([$this, 'sanitizeCsvCell'], [
                        $row->id,
                        $row->name,
                        $row->email,
                        trim(($row->countrycode ?? '') . ' ' . ($row->mobile_no ?? '')),
                        $row->referral_code ?: 'Not Generated',
                        number_format((float) ($row->referral_discount_percent ?? 10.00), 2, '.', ''),
                        $row->referralExecutive?->name ?? 'System',
                        $row->referral_prefix ?: ($row->referralExecutive?->referral_prefix ?: ($row->referral_code ? 'AIN' : '')),
                        optional($row->referral_code_generated_at)->format('Y-m-d H:i:s') ?: '',
                        (int) ($row->referred_signups_count ?? 0),
                        (int) ($row->referred_orders_count ?? 0),
                        (int) ($row->awarded_bonus_orders_count ?? 0),
                        number_format((float) ($row->referred_orders_revenue ?? 0), 2, '.', ''),
                        number_format((float) ($row->referred_discount_given ?? 0), 2, '.', ''),
                        number_format((float) ($row->total_referral_earnings ?? 0), 2, '.', ''),
                        number_format($walletVal, 2, '.', ''),
                        $row->referralUpdatedBy?->name ?? 'Auto / System',
                    ]));
                }
            } elseif ($activeTab === 'orders' && $schemaReady['orders']) {
                fputcsv($handle, [
                    'Order DB ID',
                    'Order Code',
                    'Order Date',
                    'Project Status',
                    'Buyer Name',
                    'Buyer Email',
                    'Referrer Name',
                    'Referral Code Applied',
                    'Executive',
                    'Original Amount (GBP)',
                    'Discount Given (GBP)',
                    'Final Payable (GBP)',
                    'Paid Received (GBP)',
                    'Bonus Status',
                    'Bonus Amount (GBP)',
                    'Bonus Awarded At',
                ]);

                $rows = $this->applyOrdersQuery($filters)
                    ->with([
                        'user:id,name,email',
                        'referredByUser:id,name,referral_code,referral_executive_id,referral_prefix',
                        'referredByUser.referralExecutive:id,name,referral_prefix',
                        'lead:id,order_id,is_converted',
                    ])
                    ->orderByDesc('orders.id')
                    ->get();

                $statuses = $this->resolveOrderStatusesForPage($rows->all());

                foreach ($rows as $order) {
                    $st = $statuses[$order->id] ?? ['label' => 'Pending', 'bonus_amount' => 0];
                    $payable = (float) ($order->amount ?? 0);
                    $disc = (float) ($order->coupon_discount_amount ?? 0);
                    $orig = $order->coupon_original_amount !== null
                        ? (float) $order->coupon_original_amount
                        : ($payable + $disc);

                    fputcsv($handle, array_map([$this, 'sanitizeCsvCell'], [
                        $order->id,
                        $order->order_id,
                        $order->order_date ?: optional($order->created_at)->format('Y-m-d'),
                        $order->projectstatus ?: 'Pending',
                        $order->user?->name ?? 'N/A',
                        $order->user?->email ?? '',
                        $order->referredByUser?->name ?? 'N/A',
                        $order->coupon_code ?: ($order->referredByUser?->referral_code ?? ''),
                        $order->referredByUser?->referralExecutive?->name ?? 'System',
                        number_format($orig, 2, '.', ''),
                        number_format($disc, 2, '.', ''),
                        number_format($payable, 2, '.', ''),
                        number_format((float) ($order->received_amount ?? 0), 2, '.', ''),
                        $st['label'],
                        number_format((float) $st['bonus_amount'], 2, '.', ''),
                        optional($order->referral_bonus_awarded_at)->format('Y-m-d H:i:s') ?: '',
                    ]));
                }
            } elseif ($activeTab === 'signups') {
                fputcsv($handle, [
                    'User ID',
                    'Referred Client Name',
                    'Email',
                    'Phone',
                    'Signup Date',
                    'Referred By',
                    'Referrer Code',
                    'Executive',
                    'Total Orders',
                    'Genuine Orders',
                    'Bonus Awarded Orders',
                    'Lifecycle Status',
                    'Client Own Code',
                ]);

                $rows = $this->withSignupsAggregates(
                    $this->applySignupsQuery($filters, $schemaReady),
                    $schemaReady
                )
                    ->orderByDesc('users.id')
                    ->get();

                $statuses = $this->resolveSignupStatuses($rows->all());

                foreach ($rows as $row) {
                    $st = $statuses[$row->id] ?? ['label' => 'Signed Up Only (No Order)'];
                    fputcsv($handle, array_map([$this, 'sanitizeCsvCell'], [
                        $row->id,
                        $row->name,
                        $row->email,
                        trim(($row->countrycode ?? '') . ' ' . ($row->mobile_no ?? '')),
                        optional($row->created_at)->format('Y-m-d H:i:s') ?: '',
                        $row->referrer?->name ?? ('User #' . $row->refer_id),
                        $row->referrer?->referral_code ?? '',
                        $row->referrer?->referralExecutive?->name ?? 'System',
                        (int) ($row->total_orders_count ?? 0),
                        (int) ($row->genuine_orders_count ?? 0),
                        (int) ($row->bonus_awarded_orders_count ?? 0),
                        $st['label'],
                        $row->referral_code ?: 'Not Generated',
                    ]));
                }
            } elseif ($activeTab === 'executives') {
                fputcsv($handle, [
                    'Staff ID',
                    'Name',
                    'Email',
                    'Role',
                    '3-Char Prefix',
                    'Clients Coded',
                    'Referred Signups',
                    'Referred Orders',
                    'Referred Revenue (GBP)',
                    'Discount Given (GBP)',
                    'Bonuses Unlocked (GBP)',
                ]);

                foreach ($this->buildExecutivePerformance($filters, $schemaReady) as $exec) {
                    fputcsv($handle, array_map([$this, 'sanitizeCsvCell'], [
                        $exec->id,
                        $exec->name,
                        $exec->email,
                        $exec->role_label,
                        $exec->referral_prefix ?: 'Auto',
                        $exec->coded_clients_count,
                        $exec->referred_signups_count,
                        $exec->referred_orders_count,
                        number_format($exec->referred_revenue, 2, '.', ''),
                        number_format($exec->discount_given, 2, '.', ''),
                        number_format($exec->bonuses_unlocked, 2, '.', ''),
                    ]));
                }
            } elseif ($activeTab === 'wallet') {
                fputcsv($handle, [
                    'Transaction ID',
                    'Date',
                    'Referrer ID',
                    'Referrer Name',
                    'Referrer Email',
                    'Referral Code',
                    'Executive',
                    'Type',
                    'Amount (GBP)',
                    'Balance After (GBP)',
                    'Current Wallet (GBP)',
                    'Expires At',
                    'Status',
                    'Description',
                ]);

                $rows = $this->applyWalletQuery($filters)
                    ->with(['user:id,name,email,referral_code,Wallet,referral_executive_id', 'user.referralExecutive:id,name'])
                    ->orderByDesc('id')
                    ->get();

                foreach ($rows as $tx) {
                    $liveWallet = is_numeric($tx->user?->Wallet) ? (float) $tx->user->Wallet : 0.0;
                    $expiryStatus = $tx->expires_at
                        ? ($tx->expired_processed ? 'Expired' : 'Active')
                        : 'No Expiry';

                    fputcsv($handle, array_map([$this, 'sanitizeCsvCell'], [
                        $tx->id,
                        optional($tx->created_at)->format('Y-m-d H:i:s') ?: '',
                        $tx->user_id,
                        $tx->user?->name ?? 'N/A',
                        $tx->user?->email ?? '',
                        $tx->user?->referral_code ?? '',
                        $tx->user?->referralExecutive?->name ?? 'System',
                        strtoupper((string) $tx->type),
                        number_format((float) $tx->amount, 2, '.', ''),
                        number_format((float) $tx->balance_after, 2, '.', ''),
                        number_format($liveWallet, 2, '.', ''),
                        optional($tx->expires_at)->format('Y-m-d') ?: '',
                        $expiryStatus,
                        $tx->description,
                    ]));
                }
            }

            fclose($handle);
        }, $fileName, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    protected function extractFilters(Request $request): array
    {
        return [
            'search' => trim((string) $request->input('search', '')),
            'executive_id' => $request->filled('executive_id') && is_numeric($request->input('executive_id'))
                ? (int) $request->input('executive_id')
                : null,
            'referrer_id' => $request->filled('referrer_id') && is_numeric($request->input('referrer_id'))
                ? (int) $request->input('referrer_id')
                : null,
            'from_date' => trim((string) $request->input('from_date', '')),
            'to_date' => trim((string) $request->input('to_date', '')),
            'status' => trim((string) $request->input('status', '')),
        ];
    }

    /**
     * @return array{users: bool, orders: bool, wallet: bool}
     */
    protected function isReferralSchemaReady(): array
    {
        return [
            'users' => Schema::hasColumn('users', 'referral_code_generated_at')
                && Schema::hasColumn('users', 'referral_discount_percent')
                && Schema::hasColumn('users', 'referral_executive_id')
                && Schema::hasColumn('users', 'referral_prefix'),
            'orders' => Schema::hasColumn('orders', 'referred_by_user_id')
                && Schema::hasColumn('orders', 'referral_bonus_awarded_at'),
            'wallet' => Schema::hasTable('wallet_transactions'),
        ];
    }

    /**
     * @param  array{users: bool, orders: bool, wallet: bool}  $schemaReady
     * @return array<string, int|float>
     */
    protected function buildSummaryStats(array $schemaReady): array
    {
        $codedClients = User::where('role_id', 2)
            ->where('flag', 0)
            ->whereNotNull('referral_code')
            ->where('referral_code', '!=', '')
            ->count();

        $customDiscountClients = $schemaReady['users']
            ? User::where('role_id', 2)
                ->where('flag', 0)
                ->whereNotNull('referral_code')
                ->where('referral_code', '!=', '')
                ->whereNotNull('referral_discount_percent')
                ->where('referral_discount_percent', '!=', ReferralCodeService::DEFAULT_DISCOUNT_PERCENT)
                ->count()
            : 0;

        $totalExecutives = User::where('flag', 0)
            ->whereIn('role_id', ReferralCodeService::STAFF_EXECUTIVE_ROLE_IDS)
            ->count();

        $codedExecutives = $schemaReady['users']
            ? User::where('flag', 0)
                ->whereIn('role_id', ReferralCodeService::STAFF_EXECUTIVE_ROLE_IDS)
                ->whereNotNull('referral_prefix')
                ->where('referral_prefix', '!=', '')
                ->count()
            : 0;

        $referredClients = User::where('role_id', 2)
            ->where('flag', 0)
            ->whereNotNull('refer_id')
            ->where('refer_id', '>', 0)
            ->count();

        $referredOrdersTotal = 0;
        $referredOrdersAwarded = 0;
        $referredOrdersCancelled = 0;
        $referredOrdersPending = 0;
        $totalDiscountGiven = 0.0;
        $totalReferredRevenue = 0.0;

        if ($schemaReady['orders']) {
            $orderAgg = DB::table('orders')
                ->whereNotNull('referred_by_user_id')
                ->selectRaw("
                    COUNT(*) as total_orders,
                    SUM(CASE WHEN referral_bonus_awarded_at IS NOT NULL THEN 1 ELSE 0 END) as awarded_orders,
                    SUM(CASE WHEN LOWER(TRIM(COALESCE(projectstatus, ''))) IN ('cancelled', 'trash') THEN 1 ELSE 0 END) as cancelled_orders,
                    SUM(CASE WHEN referral_bonus_awarded_at IS NULL AND LOWER(TRIM(COALESCE(projectstatus, ''))) NOT IN ('cancelled', 'trash') THEN 1 ELSE 0 END) as pending_orders,
                    COALESCE(SUM(CASE WHEN LOWER(TRIM(COALESCE(projectstatus, ''))) NOT IN ('cancelled', 'trash') THEN COALESCE(coupon_discount_amount, 0) ELSE 0 END), 0) as total_discount,
                    COALESCE(SUM(CASE WHEN LOWER(TRIM(COALESCE(projectstatus, ''))) NOT IN ('cancelled', 'trash') THEN COALESCE(amount, 0) ELSE 0 END), 0) as total_revenue
                ")
                ->first();

            if ($orderAgg) {
                $referredOrdersTotal = (int) $orderAgg->total_orders;
                $referredOrdersAwarded = (int) $orderAgg->awarded_orders;
                $referredOrdersCancelled = (int) $orderAgg->cancelled_orders;
                $referredOrdersPending = (int) $orderAgg->pending_orders;
                $totalDiscountGiven = (float) $orderAgg->total_discount;
                $totalReferredRevenue = (float) $orderAgg->total_revenue;
            }
        }

        $totalBonusCredited = (float) User::where('role_id', 2)
            ->where('flag', 0)
            ->sum('total_referral_earnings');

        $totalReferrerWallet = (float) (User::where('role_id', 2)
            ->where('flag', 0)
            ->where(function ($q) {
                $q->where(function ($cq) {
                    $cq->whereNotNull('referral_code')->where('referral_code', '!=', '');
                })
                ->orWhere('total_referral_earnings', '>', 0);
            })
            ->selectRaw("COALESCE(SUM(CASE WHEN TRIM(COALESCE(Wallet, '')) REGEXP '^-?[0-9]+(\\.[0-9]+)?$' THEN CAST(TRIM(Wallet) AS DECIMAL(12,2)) ELSE 0 END), 0) as wallet_sum")
            ->value('wallet_sum') ?? 0);

        $walletLedgerSum = $schemaReady['wallet']
            ? (float) WalletTransaction::where('type', 'credit')
                ->where(function ($q) {
                    $q->where('description', 'like', '%Referral bonus%')
                      ->orWhere('description', 'like', '%referral%');
                })
                ->sum('amount')
            : 0.0;

        return [
            'coded_clients' => $codedClients,
            'custom_discount_clients' => $customDiscountClients,
            'coded_executives' => $codedExecutives,
            'total_executives' => $totalExecutives,
            'referred_clients' => $referredClients,
            'referred_orders_total' => $referredOrdersTotal,
            'referred_orders_awarded' => $referredOrdersAwarded,
            'referred_orders_pending' => $referredOrdersPending,
            'referred_orders_cancelled' => $referredOrdersCancelled,
            'total_discount_given' => $totalDiscountGiven,
            'total_referred_revenue' => $totalReferredRevenue,
            'total_bonus_credited' => $totalBonusCredited,
            'total_referrer_wallet' => $totalReferrerWallet,
            'wallet_ledger_sum' => $walletLedgerSum,
        ];
    }

    /**
     * @param  array<string, mixed>  $filters
     * @param  array{users: bool, orders: bool, wallet: bool}  $schemaReady
     * @return array<string, int>
     */
    protected function buildTabCounts(array $filters, array $schemaReady, string $activeTab): array
    {
        $shared = array_merge($filters, ['status' => '']);

        return [
            'clients' => $this->applyClientsQuery($activeTab === 'clients' ? $filters : $shared, $schemaReady)->count(),
            'orders' => $schemaReady['orders']
                ? $this->applyOrdersQuery($activeTab === 'orders' ? $filters : $shared)->count()
                : 0,
            'signups' => $this->applySignupsQuery($activeTab === 'signups' ? $filters : $shared, $schemaReady)->count(),
            'executives' => $this->applyExecutivesBaseQuery($shared)->count(),
            'wallet' => $schemaReady['wallet'] ? $this->applyWalletQuery($shared)->count() : 0,
        ];
    }

    /**
     * @param  array<string, mixed>  $filters
     * @param  array{users: bool, orders: bool, wallet: bool}  $schemaReady
     */
    protected function applyClientsQuery(array $filters, array $schemaReady)
    {
        $query = User::query()
            ->where('users.role_id', 2)
            ->where('users.flag', 0)
            ->where(function ($q) use ($schemaReady) {
                $q->where(function ($cq) {
                    $cq->whereNotNull('users.referral_code')
                       ->where('users.referral_code', '!=', '');
                })
                ->orWhere('users.total_referral_earnings', '>', 0)
                ->orWhereExists(function ($sub) {
                    $sub->select(DB::raw(1))
                        ->from('users as ru')
                        ->whereColumn('ru.refer_id', 'users.id')
                        ->where('ru.flag', 0);
                });

                if ($schemaReady['orders']) {
                    $q->orWhereExists(function ($sub) {
                        $sub->select(DB::raw(1))
                            ->from('orders as ro')
                            ->whereColumn('ro.referred_by_user_id', 'users.id');
                    });
                }
            });

        if (! empty($filters['referrer_id'])) {
            $query->where('users.id', $filters['referrer_id']);
        }

        if (! empty($filters['executive_id']) && $schemaReady['users']) {
            $query->where('users.referral_executive_id', $filters['executive_id']);
        }

        if ($filters['search'] !== '') {
            $search = $filters['search'];
            $query->where(function ($q) use ($search, $schemaReady) {
                $q->where('users.name', 'like', "%{$search}%")
                  ->orWhere('users.email', 'like', "%{$search}%")
                  ->orWhere('users.mobile_no', 'like', "%{$search}%")
                  ->orWhere('users.referral_code', 'like', "%{$search}%");
                if ($schemaReady['users']) {
                    $q->orWhere('users.referral_prefix', 'like', "%{$search}%");
                }
                if (is_numeric($search)) {
                    $q->orWhere('users.id', (int) $search);
                }
            });
        }

        if ($filters['from_date'] !== '') {
            $from = $filters['from_date'];
            if ($schemaReady['users']) {
                $query->whereDate(DB::raw('COALESCE(users.referral_code_generated_at, users.created_at)'), '>=', $from);
            } else {
                $query->whereDate('users.created_at', '>=', $from);
            }
        }

        if ($filters['to_date'] !== '') {
            $to = $filters['to_date'];
            if ($schemaReady['users']) {
                $query->whereDate(DB::raw('COALESCE(users.referral_code_generated_at, users.created_at)'), '<=', $to);
            } else {
                $query->whereDate('users.created_at', '<=', $to);
            }
        }

        if ($filters['status'] === 'coded') {
            $query->whereNotNull('users.referral_code')->where('users.referral_code', '!=', '');
        } elseif ($filters['status'] === 'bonus_earned') {
            $query->where('users.total_referral_earnings', '>', 0);
        } elseif ($filters['status'] === 'custom_discount' && $schemaReady['users']) {
            $query->whereNotNull('users.referral_code')
                ->where('users.referral_code', '!=', '')
                ->whereNotNull('users.referral_discount_percent')
                ->where('users.referral_discount_percent', '!=', ReferralCodeService::DEFAULT_DISCOUNT_PERCENT);
        } elseif ($filters['status'] === 'with_referrals') {
            $query->where(function ($q) use ($schemaReady) {
                $q->whereExists(function ($sub) {
                    $sub->select(DB::raw(1))
                        ->from('users as ru')
                        ->whereColumn('ru.refer_id', 'users.id')
                        ->where('ru.flag', 0);
                });
                if ($schemaReady['orders']) {
                    $q->orWhereExists(function ($sub) {
                        $sub->select(DB::raw(1))
                            ->from('orders as ro')
                            ->whereColumn('ro.referred_by_user_id', 'users.id');
                    });
                }
            });
        }

        return $query;
    }

    /**
     * Attach consistent relations and referral aggregates to a clients query.
     *
     * @param  array{users: bool, orders: bool, wallet: bool}  $schemaReady
     */
    protected function withClientsAggregates($query, array $schemaReady)
    {
        $query->with([
            'referralExecutive:id,name,referral_prefix,role_id',
            'referralUpdatedBy:id,name',
        ])
        ->withCount([
            'referredUsers as referred_signups_count' => fn ($q) => $q->where('flag', 0),
        ]);

        if ($schemaReady['orders']) {
            $query->withCount([
                'referredOrders as referred_orders_count',
                'referredOrders as awarded_bonus_orders_count' => fn ($q) => $q->whereNotNull('referral_bonus_awarded_at'),
            ])
            ->withSum([
                'referredOrders as referred_orders_revenue' => fn ($q) => $q->whereRaw("LOWER(TRIM(COALESCE(projectstatus, ''))) NOT IN ('cancelled', 'trash')"),
            ], 'amount')
            ->withSum([
                'referredOrders as referred_discount_given' => fn ($q) => $q->whereRaw("LOWER(TRIM(COALESCE(projectstatus, ''))) NOT IN ('cancelled', 'trash')"),
            ], 'coupon_discount_amount');
        }

        return $query;
    }

    /**
     * Keep Clients UI and CSV export in exact deterministic order:
     * Highest earners -> Coded clients -> Ordered referrers -> Signup referrers -> Latest generated -> ID.
     *
     * @param  array{users: bool, orders: bool, wallet: bool}  $schemaReady
     */
    protected function sortClientsQuery($query, array $schemaReady)
    {
        $query->orderByDesc('users.total_referral_earnings')
            ->orderByRaw("CASE WHEN users.referral_code IS NOT NULL AND TRIM(users.referral_code) != '' THEN 0 ELSE 1 END ASC");

        if ($schemaReady['orders']) {
            $query->orderByDesc('referred_orders_count');
        }

        $query->orderByDesc('referred_signups_count');

        if ($schemaReady['users']) {
            $query->orderByDesc('users.referral_code_generated_at');
        }

        return $query->orderByDesc('users.id');
    }

    /**
     * @param  array<string, mixed>  $filters
     */
    protected function applyOrdersQuery(array $filters)
    {
        $query = Order::query()->whereNotNull('orders.referred_by_user_id');

        if (! empty($filters['referrer_id'])) {
            $query->where('orders.referred_by_user_id', $filters['referrer_id']);
        }

        if (! empty($filters['executive_id'])) {
            $execId = $filters['executive_id'];
            $query->whereHas('referredByUser', function ($q) use ($execId) {
                $q->where('referral_executive_id', $execId);
            });
        }

        if ($filters['search'] !== '') {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('orders.order_id', 'like', "%{$search}%")
                  ->orWhere('orders.coupon_code', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($uq) use ($search) {
                      $uq->where('name', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%")
                         ->orWhere('mobile_no', 'like', "%{$search}%")
                         ->orWhere('referral_code', 'like', "%{$search}%");
                  })
                  ->orWhereHas('referredByUser', function ($rq) use ($search) {
                      $rq->where('name', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%")
                         ->orWhere('referral_code', 'like', "%{$search}%")
                         ->orWhere('referral_prefix', 'like', "%{$search}%");
                  });
            });
        }

        if ($filters['from_date'] !== '') {
            $from = $filters['from_date'];
            $query->where(function ($q) use ($from) {
                $q->whereDate('orders.order_date', '>=', $from)
                  ->orWhere(function ($sq) use ($from) {
                      $sq->whereNull('orders.order_date')->whereDate('orders.created_at', '>=', $from);
                  });
            });
        }

        if ($filters['to_date'] !== '') {
            $to = $filters['to_date'];
            $query->where(function ($q) use ($to) {
                $q->whereDate('orders.order_date', '<=', $to)
                  ->orWhere(function ($sq) use ($to) {
                      $sq->whereNull('orders.order_date')->whereDate('orders.created_at', '<=', $to);
                  });
            });
        }

        if ($filters['status'] === 'awarded') {
            $query->whereNotNull('orders.referral_bonus_awarded_at');
        } elseif ($filters['status'] === 'pending') {
            $query->whereNull('orders.referral_bonus_awarded_at')
                ->whereRaw("LOWER(TRIM(COALESCE(orders.projectstatus, ''))) NOT IN ('cancelled', 'trash')");
        } elseif ($filters['status'] === 'cancelled') {
            $query->whereRaw("LOWER(TRIM(COALESCE(orders.projectstatus, ''))) IN ('cancelled', 'trash')");
        }

        return $query;
    }

    /**
     * @param  array<string, mixed>  $filters
     * @param  array{users: bool, orders: bool, wallet: bool}  $schemaReady
     */
    protected function applySignupsQuery(array $filters, array $schemaReady)
    {
        $query = User::query()
            ->where('users.role_id', 2)
            ->where('users.flag', 0)
            ->whereNotNull('users.refer_id')
            ->where('users.refer_id', '>', 0);

        if (! empty($filters['referrer_id'])) {
            $query->where('users.refer_id', $filters['referrer_id']);
        }

        if (! empty($filters['executive_id'])) {
            $execId = $filters['executive_id'];
            $query->whereHas('referrer', function ($q) use ($execId) {
                $q->where('referral_executive_id', $execId);
            });
        }

        if ($filters['search'] !== '') {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('users.name', 'like', "%{$search}%")
                  ->orWhere('users.email', 'like', "%{$search}%")
                  ->orWhere('users.mobile_no', 'like', "%{$search}%")
                  ->orWhere('users.referral_code', 'like', "%{$search}%")
                  ->orWhereHas('referrer', function ($rq) use ($search) {
                      $rq->where('name', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%")
                         ->orWhere('referral_code', 'like', "%{$search}%")
                         ->orWhere('referral_prefix', 'like', "%{$search}%");
                  });
            });
        }

        if ($filters['from_date'] !== '') {
            $query->whereDate('users.created_at', '>=', $filters['from_date']);
        }

        if ($filters['to_date'] !== '') {
            $query->whereDate('users.created_at', '<=', $filters['to_date']);
        }

        if ($filters['status'] === 'awarded' && $schemaReady['orders']) {
            $query->whereHas('orders', fn ($q) => $q->whereNotNull('referral_bonus_awarded_at'));
        } elseif ($filters['status'] === 'pending' && $schemaReady['orders']) {
            $query->whereDoesntHave('orders', fn ($q) => $q->whereNotNull('referral_bonus_awarded_at'))
                ->whereHas('orders', function ($q) {
                    $q->whereNotNull('referred_by_user_id')
                        ->whereNull('referral_bonus_awarded_at')
                        ->whereRaw("LOWER(TRIM(COALESCE(projectstatus, ''))) NOT IN ('cancelled', 'trash')");
                });
        } elseif ($filters['status'] === 'legacy_ordered') {
            if ($schemaReady['orders']) {
                $query->whereDoesntHave('orders', fn ($q) => $q->whereNotNull('referred_by_user_id'));
            }
            $query->whereHas('genuineOrders');
        } elseif ($filters['status'] === 'no_order') {
            $query->whereDoesntHave('orders');
        }

        return $query;
    }

    /**
     * @param  array{users: bool, orders: bool, wallet: bool}  $schemaReady
     */
    protected function withSignupsAggregates($query, array $schemaReady)
    {
        $query->with([
            'referrer:id,name,email,referral_code,referral_prefix,referral_discount_percent,referral_executive_id',
            'referrer.referralExecutive:id,name,referral_prefix',
        ]);

        $counts = [
            'orders as total_orders_count',
            'genuineOrders as genuine_orders_count',
        ];

        if ($schemaReady['orders']) {
            $counts['orders as bonus_awarded_orders_count'] = fn ($q) => $q->whereNotNull('referral_bonus_awarded_at');
            $counts['orders as pending_bonus_orders_count'] = fn ($q) => $q->whereNotNull('referred_by_user_id')
                ->whereNull('referral_bonus_awarded_at')
                ->whereRaw("LOWER(TRIM(COALESCE(projectstatus, ''))) NOT IN ('cancelled', 'trash')");
        }

        return $query->withCount($counts);
    }

    /**
     * Resolve accurate lifecycle status for each referred signup (matches UI & CSV export).
     *
     * @param  array<int, User>  $signups
     * @return array<int, array{code: string, label: string, badge: string}>
     */
    protected function resolveSignupStatuses(array $signups): array
    {
        $result = [];
        foreach ($signups as $signup) {
            $totalOrders = (int) ($signup->total_orders_count ?? 0);
            $genuineOrders = (int) ($signup->genuine_orders_count ?? 0);
            $bonusOrders = (int) ($signup->bonus_awarded_orders_count ?? 0);
            $pendingBonusOrders = (int) ($signup->pending_bonus_orders_count ?? 0);

            if ($bonusOrders > 0) {
                $result[$signup->id] = [
                    'code' => 'awarded',
                    'label' => 'Bonus Unlocked (10%)',
                    'badge' => 'badge-light-success',
                ];
            } elseif ($pendingBonusOrders > 0) {
                $result[$signup->id] = [
                    'code' => 'pending',
                    'label' => 'Pending Bonus Conversion',
                    'badge' => 'badge-light-warning',
                ];
            } elseif ($genuineOrders > 0) {
                $result[$signup->id] = [
                    'code' => 'legacy_ordered',
                    'label' => 'Ordered (No Code / Legacy)',
                    'badge' => 'badge-light-primary',
                ];
            } elseif ($totalOrders > 0) {
                $result[$signup->id] = [
                    'code' => 'draft_or_cancelled',
                    'label' => 'Draft / Cancelled Order',
                    'badge' => 'badge-light-secondary',
                ];
            } else {
                $result[$signup->id] = [
                    'code' => 'no_order',
                    'label' => 'Signed Up Only (No Order)',
                    'badge' => 'badge-light-secondary',
                ];
            }
        }

        return $result;
    }

    /**
     * @param  array<string, mixed>  $filters
     */
    protected function applyExecutivesBaseQuery(array $filters)
    {
        $query = User::query()
            ->where('users.flag', 0)
            ->whereIn('users.role_id', ReferralCodeService::STAFF_EXECUTIVE_ROLE_IDS);

        if (! empty($filters['executive_id'])) {
            $query->where('users.id', $filters['executive_id']);
        }

        if ($filters['search'] !== '') {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('users.name', 'like', "%{$search}%")
                  ->orWhere('users.email', 'like', "%{$search}%")
                  ->orWhere('users.referral_prefix', 'like', "%{$search}%");
            });
        }

        return $query;
    }

    /**
     * @param  array<string, mixed>  $filters
     * @param  array{users: bool, orders: bool, wallet: bool}  $schemaReady
     */
    protected function buildExecutivePerformance(array $filters, array $schemaReady)
    {
        $executives = $this->applyExecutivesBaseQuery($filters)
            ->orderByRaw('CASE WHEN referral_prefix IS NOT NULL AND referral_prefix != "" THEN 0 ELSE 1 END, name ASC')
            ->get(['id', 'name', 'email', 'role_id', 'referral_prefix']);

        $execIds = $executives->pluck('id')->all();
        if (empty($execIds) || ! $schemaReady['users']) {
            return $executives->map(function ($exec) {
                $exec->role_label = $this->resolveRoleLabel((int) $exec->role_id);
                $exec->coded_clients_count = 0;
                $exec->referred_signups_count = 0;
                $exec->referred_orders_count = 0;
                $exec->referred_revenue = 0.0;
                $exec->discount_given = 0.0;
                $exec->bonuses_unlocked = 0.0;

                return $exec;
            });
        }

        $codedQuery = DB::table('users')
            ->whereIn('referral_executive_id', $execIds)
            ->where('role_id', 2)
            ->where('flag', 0)
            ->whereNotNull('referral_code')
            ->where('referral_code', '!=', '');
        if ($filters['from_date'] !== '') {
            $codedQuery->whereDate(DB::raw('COALESCE(referral_code_generated_at, created_at)'), '>=', $filters['from_date']);
        }
        if ($filters['to_date'] !== '') {
            $codedQuery->whereDate(DB::raw('COALESCE(referral_code_generated_at, created_at)'), '<=', $filters['to_date']);
        }
        $codedStats = $codedQuery
            ->selectRaw('referral_executive_id, COUNT(*) as coded_count')
            ->groupBy('referral_executive_id')
            ->pluck('coded_count', 'referral_executive_id');

        $signupQuery = DB::table('users as child')
            ->join('users as parent', 'parent.id', '=', 'child.refer_id')
            ->where('child.role_id', 2)
            ->where('child.flag', 0)
            ->where('parent.flag', 0)
            ->whereIn('parent.referral_executive_id', $execIds);
        if ($filters['from_date'] !== '') {
            $signupQuery->whereDate('child.created_at', '>=', $filters['from_date']);
        }
        if ($filters['to_date'] !== '') {
            $signupQuery->whereDate('child.created_at', '<=', $filters['to_date']);
        }
        $signupStats = $signupQuery
            ->selectRaw('parent.referral_executive_id, COUNT(child.id) as signup_count')
            ->groupBy('parent.referral_executive_id')
            ->pluck('signup_count', 'referral_executive_id');

        $orderStats = collect();
        if ($schemaReady['orders']) {
            $orderQuery = DB::table('orders as o')
                ->join('users as ref', 'ref.id', '=', 'o.referred_by_user_id')
                ->whereIn('ref.referral_executive_id', $execIds);
            if ($filters['from_date'] !== '') {
                $from = $filters['from_date'];
                $orderQuery->whereRaw('COALESCE(o.order_date, DATE(o.created_at)) >= ?', [$from]);
            }
            if ($filters['to_date'] !== '') {
                $to = $filters['to_date'];
                $orderQuery->whereRaw('COALESCE(o.order_date, DATE(o.created_at)) <= ?', [$to]);
            }
            $orderStats = $orderQuery
                ->selectRaw("
                    ref.referral_executive_id as exec_id,
                    COUNT(o.id) as total_orders,
                    COALESCE(SUM(CASE WHEN LOWER(TRIM(COALESCE(o.projectstatus, ''))) NOT IN ('cancelled', 'trash') THEN COALESCE(o.amount, 0) ELSE 0 END), 0) as total_revenue,
                    COALESCE(SUM(CASE WHEN LOWER(TRIM(COALESCE(o.projectstatus, ''))) NOT IN ('cancelled', 'trash') THEN COALESCE(o.coupon_discount_amount, 0) ELSE 0 END), 0) as total_discount,
                    COALESCE(SUM(CASE WHEN o.referral_bonus_awarded_at IS NOT NULL THEN ROUND(COALESCE(o.amount, 0) * 0.10, 2) ELSE 0 END), 0) as total_bonus
                ")
                ->groupBy('ref.referral_executive_id')
                ->get()
                ->keyBy('exec_id');
        }

        return $executives->map(function ($exec) use ($codedStats, $signupStats, $orderStats) {
            $oStat = $orderStats->get($exec->id);
            $exec->role_label = $this->resolveRoleLabel((int) $exec->role_id);
            $exec->coded_clients_count = (int) ($codedStats[$exec->id] ?? 0);
            $exec->referred_signups_count = (int) ($signupStats[$exec->id] ?? 0);
            $exec->referred_orders_count = (int) ($oStat->total_orders ?? 0);
            $exec->referred_revenue = (float) ($oStat->total_revenue ?? 0.0);
            $exec->discount_given = (float) ($oStat->total_discount ?? 0.0);
            $exec->bonuses_unlocked = (float) ($oStat->total_bonus ?? 0.0);

            return $exec;
        })->sortByDesc(fn ($e) => ($e->coded_clients_count * 1000) + $e->referred_orders_count)->values();
    }

    /**
     * @param  array<string, mixed>  $filters
     */
    protected function applyWalletQuery(array $filters)
    {
        $query = WalletTransaction::query()
            ->where(function ($q) {
                $q->where('description', 'like', '%Referral bonus%')
                  ->orWhere('description', 'like', '%referral%');
            });

        if (! empty($filters['referrer_id'])) {
            $query->where('user_id', $filters['referrer_id']);
        }

        if (! empty($filters['executive_id'])) {
            $execId = $filters['executive_id'];
            $query->whereHas('user', function ($q) use ($execId) {
                $q->where('referral_executive_id', $execId);
            });
        }

        if ($filters['search'] !== '') {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($uq) use ($search) {
                      $uq->where('name', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%")
                         ->orWhere('referral_code', 'like', "%{$search}%")
                         ->orWhere('referral_prefix', 'like', "%{$search}%");
                  });
            });
        }

        if ($filters['from_date'] !== '') {
            $query->whereDate('created_at', '>=', $filters['from_date']);
        }

        if ($filters['to_date'] !== '') {
            $query->whereDate('created_at', '<=', $filters['to_date']);
        }

        return $query;
    }

    /**
     * Batch-resolve conversion & bonus status for a page of orders without N+1 queries.
     *
     * @param  array<int, Order>  $orders
     * @return array<int, array{code: string, label: string, badge: string, bonus_amount: float}>
     */
    protected function resolveOrderStatusesForPage(array $orders): array
    {
        if (empty($orders)) {
            return [];
        }

        $orderCodes = collect($orders)->pluck('order_id')->filter()->unique()->values()->all();
        $unconvertedByOrderCode = ! empty($orderCodes)
            ? Leads::where('is_converted', 0)
                ->whereIn('order_id', $orderCodes)
                ->where(function ($q) {
                    $q->where('duplicate_lead', 0)->orWhereNull('duplicate_lead');
                })
                ->pluck('order_id')
                ->flip()
                ->all()
            : [];

        $result = [];
        foreach ($orders as $order) {
            $payable = is_numeric($order->amount) ? (float) $order->amount : 0.0;
            $calcBonus = $payable > 0
                ? round($payable * (ReferralCodeService::DEFAULT_REFERRER_BONUS_PERCENT / 100), 2)
                : 0.0;
            $status = strtolower(trim((string) ($order->projectstatus ?? '')));

            if (! empty($order->referral_bonus_awarded_at)) {
                $result[$order->id] = [
                    'code' => 'awarded',
                    'label' => 'Bonus Awarded',
                    'badge' => 'badge-light-success',
                    'bonus_amount' => $calcBonus,
                ];
                continue;
            }

            if (in_array($status, ['cancelled', 'trash'], true)) {
                $result[$order->id] = [
                    'code' => 'cancelled',
                    'label' => 'Not Eligible (' . ucfirst($status) . ')',
                    'badge' => 'badge-light-danger',
                    'bonus_amount' => 0.0,
                ];
                continue;
            }

            $isLeadUnconverted = ($order->lead && (int) ($order->lead->is_converted ?? 1) === 0)
                || (! empty($order->order_id) && isset($unconvertedByOrderCode[$order->order_id]));
            $hasOrderDate = ! empty($order->order_date);
            $hasConfirmedStatus = ! in_array($status, ['', 'pending'], true);

            if ($isLeadUnconverted) {
                $result[$order->id] = [
                    'code' => 'pending_lead',
                    'label' => 'Pending Lead Conversion',
                    'badge' => 'badge-light-warning',
                    'bonus_amount' => $calcBonus,
                ];
                continue;
            }

            if (! $hasOrderDate && ! $hasConfirmedStatus) {
                $result[$order->id] = [
                    'code' => 'pending_order',
                    'label' => 'Pending Confirmation',
                    'badge' => 'badge-light-warning',
                    'bonus_amount' => $calcBonus,
                ];
                continue;
            }

            $result[$order->id] = [
                'code' => 'skipped',
                'label' => 'Converted (No Bonus — Repeat / £0)',
                'badge' => 'badge-light-secondary',
                'bonus_amount' => 0.0,
            ];
        }

        return $result;
    }

    protected function resolveRoleLabel(int $roleId): string
    {
        return match ($roleId) {
            1 => 'Super Admin',
            4 => 'Marketing Team',
            9 => 'Sub Admin',
            default => 'Staff #' . $roleId,
        };
    }

    protected function sanitizeCsvCell($value): string
    {
        $str = (string) ($value ?? '');
        if ($str !== '' && preg_match('/^[=+\-@\t\r]/', $str)) {
            return "'" . $str;
        }

        return $str;
    }
}
