<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class MultipleOrderClientFilterTest extends TestCase
{
    protected string $prefix = 'ZZTEST_MOCF_';

    protected function setUp(): void
    {
        parent::setUp();
        $this->cleanupSyntheticFixtures();
        DB::beginTransaction();
    }

    protected function tearDown(): void
    {
        while (DB::transactionLevel() > 0) {
            DB::rollBack();
        }
        $this->cleanupSyntheticFixtures();
        parent::tearDown();
    }

    private function cleanupSyntheticFixtures(): void
    {
        DB::table('orders')->where('title', 'like', $this->prefix . '%')->delete();
        DB::table('leads')
            ->where('user_name', 'like', $this->prefix . '%')
            ->orWhere('order_id', 'like', 'ZZUNCONV_%')
            ->orWhere('order_id', 'like', 'ZZCOLLIDE_%')
            ->delete();
        DB::table('users')->where('name', 'like', $this->prefix . '%')->delete();
    }

    private function createUser(array $overrides = []): User
    {
        static $seq = 1;
        $n = $seq++;
        $id = DB::table('users')->insertGetId(array_merge([
            'name' => $this->prefix . 'User_' . $n,
            'email' => strtolower($this->prefix) . 'u' . $n . '@example.test',
            'countrycode' => '+44',
            'mobile_no' => '790000' . str_pad((string) $n, 4, '0', STR_PAD_LEFT),
            'role_id' => 2,
            'flag' => 0,
            'password' => Hash::make('secret123'),
            'created_at' => '2026-01-15 10:00:00',
            'updated_at' => '2026-01-15 10:00:00',
        ], $overrides));

        return User::findOrFail($id);
    }

    private function createLead(array $overrides = []): int
    {
        static $seq = 1;
        $n = $seq++;
        return DB::table('leads')->insertGetId(array_merge([
            'order_id' => 'ZZLEADC' . $n,
            'emp_id' => null,
            'user_name' => $this->prefix . 'Lead_' . $n,
            'email' => strtolower($this->prefix) . 'l' . $n . '@example.test',
            'countrycode' => '+44',
            'mobile' => '791100' . str_pad((string) $n, 4, '0', STR_PAD_LEFT),
            'project_title' => 'Lead Title ' . $n,
            'is_converted' => 1,
            'status' => 0,
            'flag' => 0,
            'frontendorder' => 0,
            'is_app_lead' => 0,
        ], $overrides));
    }

    private function createOrder(int $uid, array $overrides = []): Order
    {
        static $seq = 1;
        $n = $seq++;
        $id = DB::table('orders')->insertGetId(array_merge([
            'uid' => $uid,
            'order_id' => 'ZZORD' . $n . '_' . uniqid(),
            'lead_id' => null,
            'order_date' => Carbon::now()->subMonths(2)->toDateString(),
            'title' => $this->prefix . 'Order_' . $n,
            'amount' => 200.00,
            'received_amount' => 200.00,
            'projectstatus' => 'Delivered',
            'status' => 0,
            'flag' => 0,
            'is_fail' => 0,
            'module_code' => 'ZZMOD_DEFAULT',
            'college_name' => 'ZZTest University Default',
        ], $overrides));

        return Order::findOrFail($id);
    }

    public function test_resolve_client_order_bounds_all_presets_and_custom_min(): void
    {
        $this->assertNull(Order::resolveClientOrderBounds(null, null));
        $this->assertNull(Order::resolveClientOrderBounds('', ''));
        $this->assertSame(['min' => 2, 'max' => null], Order::resolveClientOrderBounds('multiple'));
        $this->assertSame(['min' => 2, 'max' => 3], Order::resolveClientOrderBounds('2_to_3'));
        $this->assertSame(['min' => 4, 'max' => null], Order::resolveClientOrderBounds('Retainer'));
        $this->assertSame(['min' => 4, 'max' => null], Order::resolveClientOrderBounds('retainer'));
        $this->assertSame(['min' => 4, 'max' => null], Order::resolveClientOrderBounds('repeated'));
        $this->assertSame(['min' => 11, 'max' => null], Order::resolveClientOrderBounds('loyal'));
        $this->assertSame(['min' => 1, 'max' => 1], Order::resolveClientOrderBounds('single'));
        $this->assertSame(['min' => 1, 'max' => 3], Order::resolveClientOrderBounds('beginner'));
        $this->assertSame(['min' => 5, 'max' => null], Order::resolveClientOrderBounds(null, 5));
        $this->assertSame(['min' => 5, 'max' => null], Order::resolveClientOrderBounds('2_to_3', 5));
    }

    public function test_unconverted_leads_cancelled_trash_drafts_non_role2_and_deleted_users_are_strictly_excluded(): void
    {
        $admin = $this->createUser(['role_id' => 1, 'name' => $this->prefix . 'AdminActing']);
        $this->actingAs($admin);

        // 1. Customer with 1 genuine Delivered + 1 unconverted lead placeholder (is_converted=0)
        // Must count as 1 (NOT appear in multiple/2+, only in single)
        $uUnconvertedMix = $this->createUser(['name' => $this->prefix . 'UnconvertedMix']);
        $this->createOrder($uUnconvertedMix->id, [
            'projectstatus' => 'Delivered',
            'received_amount' => 150.00,
            'module_code' => 'ZZTEST_CASE',
            'college_name' => 'ZZTest College A',
        ]);
        $unconvLeadId = $this->createLead([
            'emp_id' => $uUnconvertedMix->id,
            'is_converted' => 0,
            'order_id' => 'ZZUNCONV_' . uniqid(),
        ]);
        $this->createOrder($uUnconvertedMix->id, [
            'lead_id' => $unconvLeadId,
            'order_id' => 'ZZUNCONV_ORD_' . uniqid(),
            'projectstatus' => 'Initiated',
            'order_date' => Carbon::now()->toDateString(),
            'received_amount' => 500.00,
            'feedback_ticket' => 'FT-FAKE',
            'is_fail' => 1,
            'module_code' => 'ZZTEST_CASE',
            'college_name' => 'ZZTest College A',
        ]);

        // 2. Customer with 1 Delivered + 1 Cancelled + 1 Trash (count must = 1)
        $uCancelledMix = $this->createUser(['name' => $this->prefix . 'CancelledMix']);
        $this->createOrder($uCancelledMix->id, [
            'projectstatus' => 'Delivered',
            'received_amount' => 120.00,
            'module_code' => 'ZZTEST_CASE',
            'college_name' => 'ZZTest College A',
        ]);
        $this->createOrder($uCancelledMix->id, [
            'projectstatus' => 'Cancelled',
            'received_amount' => 999.00,
            'feedback_ticket' => 'FT-CAN',
            'is_fail' => 1,
            'module_code' => 'ZZTEST_CASE',
            'college_name' => 'ZZTest College A',
        ]);
        $this->createOrder($uCancelledMix->id, [
            'projectstatus' => 'Trash',
            'received_amount' => 888.00,
            'module_code' => 'ZZTEST_CASE',
            'college_name' => 'ZZTest College A',
        ]);

        // 3. Customer with 1 Delivered + 2 incomplete checkout drafts (order_date IS NULL + Pending/empty)
        $uDraftMix = $this->createUser(['name' => $this->prefix . 'DraftMix']);
        $this->createOrder($uDraftMix->id, [
            'projectstatus' => 'Delivered',
            'received_amount' => 110.00,
            'module_code' => 'ZZTEST_CASE',
            'college_name' => 'ZZTest College A',
        ]);
        $this->createOrder($uDraftMix->id, [
            'order_date' => null,
            'projectstatus' => 'Pending',
            'received_amount' => 0.00,
            'module_code' => 'ZZTEST_CASE',
            'college_name' => 'ZZTest College A',
        ]);
        $this->createOrder($uDraftMix->id, [
            'order_date' => null,
            'projectstatus' => '',
            'received_amount' => 0.00,
            'module_code' => 'ZZTEST_CASE',
            'college_name' => 'ZZTest College A',
        ]);

        // 4. Non-customer (role_id=4) and soft-deleted customer (flag=1) with 3 Delivered orders each
        $uStaff = $this->createUser(['role_id' => 4, 'name' => $this->prefix . 'StaffUser']);
        $uDeleted = $this->createUser(['role_id' => 2, 'flag' => 1, 'name' => $this->prefix . 'DeletedUser']);
        foreach ([$uStaff, $uDeleted] as $nonClient) {
            for ($i = 0; $i < 3; $i++) {
                $this->createOrder($nonClient->id, [
                    'projectstatus' => 'Delivered',
                    'received_amount' => 300.00,
                    'module_code' => 'ZZTEST_CASE',
                    'college_name' => 'ZZTest College A',
                ]);
            }
        }

        // 5. Genuine Multi-Order Customer #1: 2 Delivered orders whose lead_id is NULL/missing AND one shares an order_id string
        // with an UNRELATED unconverted lead (emp_id != uid) to verify zero SQL 1267 collation errors & no false drops
        $uPrunedMulti = $this->createUser(['name' => $this->prefix . 'PrunedCollideMulti']);
        $collidingCode = 'ZZCOLLIDE_' . rand(10000, 99999);
        $this->createLead([
            'emp_id' => $uUnconvertedMix->id, // DIFFERENT user
            'is_converted' => 0,
            'order_id' => $collidingCode,
        ]);
        $oMulti1 = $this->createOrder($uPrunedMulti->id, [
            'order_id' => $collidingCode,
            'lead_id' => null,
            'order_date' => '2026-02-10',
            'projectstatus' => 'Delivered',
            'received_amount' => 210.00,
            'module_code' => 'ZZTEST_CASE',
            'college_name' => 'ZZTest College A',
        ]);
        $oMulti2 = $this->createOrder($uPrunedMulti->id, [
            'lead_id' => 999999991, // nonexistent/pruned lead row
            'order_date' => '2023-05-12', // > 18 months old to test lifetime repeat!
            'projectstatus' => 'Completed',
            'received_amount' => 240.00,
            'feedback_ticket' => 'FT-REAL',
            'module_code' => 'ZZTEST_CASE',
            'college_name' => 'ZZTest College A',
        ]);

        // 6. Genuine Retainer Customer: 4 placed converted non-cancelled orders (2 in 2026, 2 historical)
        $uRetainer = $this->createUser(['name' => $this->prefix . 'RetainerClient']);
        for ($i = 0; $i < 4; $i++) {
            $convLeadId = $this->createLead(['emp_id' => $uRetainer->id, 'is_converted' => 1]);
            $this->createOrder($uRetainer->id, [
                'lead_id' => $convLeadId,
                'order_date' => $i < 2 ? '2026-03-10' : '2022-09-01',
                'projectstatus' => $i === 0 ? 'In Progress' : 'Delivered',
                'received_amount' => 100.00,
                'is_fail' => $i === 1 ? 1 : 0,
                'module_code' => 'ZZTEST_CASE',
                'college_name' => 'ZZTest College A',
            ]);
        }

        // 7. Genuine Loyal Customer: 11 genuine orders (mix of Pending WITH order_date and Initiated WITH order_date)
        $uLoyal = $this->createUser(['name' => $this->prefix . 'LoyalClient']);
        for ($i = 0; $i < 11; $i++) {
            $this->createOrder($uLoyal->id, [
                'order_date' => '2026-04-01',
                'projectstatus' => $i === 0 ? 'Pending' : ($i === 1 ? 'Initiated' : 'Delivered'),
                'received_amount' => 50.00,
                'module_code' => 'ZZTEST_CASE',
                'college_name' => 'ZZTest College A',
            ]);
        }

        // --- A. Direct Query & Scope Verification ---
        $this->assertSame(1, $uUnconvertedMix->genuineOrders()->count());
        $this->assertSame(1, $uCancelledMix->genuineOrders()->count());
        $this->assertSame(1, $uDraftMix->genuineOrders()->count());
        $this->assertSame(2, $uPrunedMulti->genuineOrders()->count());
        $this->assertSame(4, $uRetainer->genuineOrders()->count());
        $this->assertSame(11, $uLoyal->genuineOrders()->count());

        $fixtureIds = [
            $uUnconvertedMix->id, $uCancelledMix->id, $uDraftMix->id,
            $uStaff->id, $uDeleted->id, $uPrunedMulti->id, $uRetainer->id, $uLoyal->id,
        ];

        $multiIds = User::query()->where('users.flag', 0)
            ->whereIn('users.id', $fixtureIds)
            ->filterByGenuineOrderBounds(Order::resolveClientOrderBounds('multiple'))
            ->pluck('users.id')->all();
        sort($multiIds);
        $expectedMultiIds = [$uPrunedMulti->id, $uRetainer->id, $uLoyal->id];
        sort($expectedMultiIds);
        $this->assertSame($expectedMultiIds, $multiIds);

        $retainerIds = User::query()->where('users.flag', 0)
            ->whereIn('users.id', $fixtureIds)
            ->filterByGenuineOrderBounds(Order::resolveClientOrderBounds('Retainer'))
            ->pluck('users.id')->all();
        sort($retainerIds);
        $expectedRetainerIds = [$uRetainer->id, $uLoyal->id];
        sort($expectedRetainerIds);
        $this->assertSame($expectedRetainerIds, $retainerIds);

        $loyalIds = User::query()->where('users.flag', 0)
            ->whereIn('users.id', $fixtureIds)
            ->filterByGenuineOrderBounds(Order::resolveClientOrderBounds('loyal'))
            ->pluck('users.id')->all();
        $this->assertSame([$uLoyal->id], $loyalIds);

        $singleIds = User::query()->where('users.flag', 0)
            ->whereIn('users.id', $fixtureIds)
            ->filterByGenuineOrderBounds(Order::resolveClientOrderBounds('single'))
            ->pluck('users.id')->all();
        sort($singleIds);
        $expectedSingleIds = [$uUnconvertedMix->id, $uCancelledMix->id, $uDraftMix->id];
        sort($expectedSingleIds);
        $this->assertSame($expectedSingleIds, $singleIds);

        $min5Ids = User::query()->where('users.flag', 0)
            ->whereIn('users.id', $fixtureIds)
            ->filterByGenuineOrderBounds(Order::resolveClientOrderBounds(null, 5))
            ->pluck('users.id')->all();
        $this->assertSame([$uLoyal->id], $min5Ids);

        // --- B. Option A1: /user & /export-users HTTP tests ---
        $respUserMulti = $this->get('/user?order_category=multiple&user=' . $this->prefix);
        $respUserMulti->assertOk();
        $respUserMulti->assertSee($uPrunedMulti->name);
        $respUserMulti->assertSee($uRetainer->name);
        $respUserMulti->assertSee($uLoyal->name);
        $respUserMulti->assertDontSee($uUnconvertedMix->name);
        $respUserMulti->assertDontSee($uCancelledMix->name);
        $respUserMulti->assertDontSee($uDraftMix->name);
        $respUserMulti->assertDontSee($uStaff->name);
        $respUserMulti->assertDontSee($uDeleted->name);

        // Regression test for Retainer (capital R) & lifetime default when filtering multi-order clients
        $respUserRetainer = $this->get('/user?order_category=Retainer&user=' . $this->prefix);
        $respUserRetainer->assertOk();
        $respUserRetainer->assertSee($uRetainer->name);
        $respUserRetainer->assertSee($uLoyal->name);
        $respUserRetainer->assertDontSee($uPrunedMulti->name);

        // Switching period to this_year should drop PrunedCollideMulti (only 1 order in 2026) and retain Retainer (2 orders in 2026)
        $respUserThisYear = $this->get('/user?order_category=multiple&order_period=this_year&user=' . $this->prefix);
        $respUserThisYear->assertOk();
        $respUserThisYear->assertDontSee($uPrunedMulti->name);
        $respUserThisYear->assertSee($uRetainer->name);
        $respUserThisYear->assertSee($uLoyal->name);

        // CSV Export (/export-users) - returns BinaryFileResponse via Response::download()
        $respExportUsers = $this->get('/export-users?order_category=multiple&user=' . $this->prefix);
        $respExportUsers->assertOk();
        $csvUsersPath = $respExportUsers->getFile()->getPathname();
        $csvUsers = file_get_contents($csvUsersPath);
        @unlink($csvUsersPath);
        $this->assertStringContainsString('GenuineOrders', $csvUsers);
        $this->assertStringContainsString($uPrunedMulti->name, $csvUsers);
        $this->assertStringContainsString($uRetainer->name, $csvUsers);
        $this->assertStringNotContainsString($uUnconvertedMix->name, $csvUsers);
        $this->assertStringNotContainsString($uCancelledMix->name, $csvUsers);
        $this->assertStringNotContainsString($uStaff->name, $csvUsers);
        $this->assertStringNotContainsString($uDeleted->name, $csvUsers);

        // --- C. Option A2: /user/report-list HTTP + Grand Total Accuracy tests ---
        $respReport = $this->get('/user/report-list?order_category=multiple&search=' . $this->prefix);
        $respReport->assertOk();
        $respReport->assertSee('Genuine Confirmed Client Report');
        $respReport->assertSee($uPrunedMulti->name);
        $respReport->assertSee($uRetainer->name);
        $respReport->assertSee($uLoyal->name);
        $respReport->assertDontSee($uUnconvertedMix->name);
        $respReport->assertDontSee($uCancelledMix->name);
        $respReport->assertDontSee($uDraftMix->name);
        $respReport->assertDontSee($uStaff->name);
        $respReport->assertDontSee($uDeleted->name);

        // Verify View Data totals on report-list ONLY include genuine non-cancelled converted orders:
        // uPrunedMulti: 2 orders, 450 rev, 1 ticket, 0 fail
        // uRetainer:    4 orders, 400 rev, 0 ticket, 1 fail
        // uLoyal:      11 orders, 550 rev, 0 ticket, 0 fail
        // Expected totals: 17 orders, £1,400.00 revenue, 1 ticket, 1 fail, 2 combined
        $viewData = $respReport->original->getData();
        $this->assertSame(17, $viewData['totalOrders']);
        $this->assertEquals(1400.00, $viewData['totalRevenue']);
        $this->assertSame(1, $viewData['totalTickets']);
        $this->assertSame(1, $viewData['totalFailedOrders']);
        $this->assertSame(2, $viewData['totalTicketsAndFailed']);

        // Test sorting variants on /user/report-list
        foreach (['orders_count', 'total_revenue', 'total_tickets', 'failed_orders_count', 'tickets_failed_total'] as $sortCol) {
            $this->get('/user/report-list?order_category=multiple&search=' . $this->prefix . '&sort_by=' . $sortCol . '&order=asc')
                ->assertOk();
        }

        // Test date_type=order_date filtering
        $respOrderDate = $this->get('/user/report-list?order_category=multiple&search=' . $this->prefix . '&date_type=order_date&from_date=2026-01-01&to_date=2026-12-31');
        $respOrderDate->assertOk();
        $respOrderDate->assertSee($uRetainer->name);
        $respOrderDate->assertSee($uLoyal->name);
        $respOrderDate->assertDontSee($uPrunedMulti->name);

        // --- D. Option B: /orders, /orders/filter, /export HTTP tests ---
        $respOrdersIndex = $this->get('/orders?client_orders=multiple&module_code=ZZTEST_CASE');
        $respOrdersIndex->assertOk();
        $respOrdersIndex->assertSee($collidingCode);
        $respOrdersIndex->assertSee($oMulti2->order_id);
        $respOrdersIndex->assertDontSee($uUnconvertedMix->name);
        $respOrdersIndex->assertDontSee($uCancelledMix->name);

        $respOrdersFilter = $this->getJson('/orders/filter?client_orders=multiple&module_code=ZZTEST_CASE');
        $respOrdersFilter->assertOk();
        $json = $respOrdersFilter->json();
        $this->assertSame(17, $json['total']); // 2 + 4 + 11 genuine orders
        $this->assertStringContainsString($collidingCode, $json['html']);
        $this->assertStringContainsString($oMulti2->order_id, $json['html']);
        $this->assertStringNotContainsString($uUnconvertedMix->name, $json['html']);
        $this->assertStringNotContainsString($uCancelledMix->name, $json['html']);
        $this->assertStringNotContainsString($uDraftMix->name, $json['html']);
        $this->assertStringNotContainsString($uStaff->name, $json['html']);
        $this->assertStringNotContainsString($uDeleted->name, $json['html']);

        // Combined filter: client_orders=retainer + status=In Progress -> only Retainer's 1 In Progress order
        $respComboStatus = $this->getJson('/orders/filter?client_orders=retainer&status=In+Progress&module_code=ZZTEST_CASE');
        $respComboStatus->assertOk();
        $this->assertSame(1, $respComboStatus->json('total'));

        // Combined filter: client_orders=multiple + month=2026-04 (must NOT hit legacy early-return)
        $respComboMonth = $this->getJson('/orders/filter?client_orders=multiple&month=2026-04&module_code=ZZTEST_CASE');
        $respComboMonth->assertOk();
        $this->assertSame(11, $respComboMonth->json('total'));

        // CSV Export (/export)
        $respExportOrders = $this->get('/export?client_orders=multiple&module_code=ZZTEST_CASE');
        $respExportOrders->assertOk();
        $csvOrders = $respExportOrders->streamedContent();
        $this->assertStringContainsString($collidingCode, $csvOrders);
        $this->assertStringContainsString($oMulti2->order_id, $csvOrders);
        $this->assertStringNotContainsString($uCancelledMix->name, $csvOrders);
        $this->assertStringNotContainsString($uUnconvertedMix->name, $csvOrders);
    }

    public function test_non_super_admin_access_hides_revenue_on_report_list_while_preserving_multi_order_filter(): void
    {
        $multiUser = $this->createUser(['name' => $this->prefix . 'RoleCheckClient']);
        $this->createOrder($multiUser->id, ['projectstatus' => 'Delivered', 'received_amount' => 500, 'module_code' => 'ZZTEST_ROLE']);
        $this->createOrder($multiUser->id, ['projectstatus' => 'Completed', 'received_amount' => 500, 'module_code' => 'ZZTEST_ROLE']);

        $marketingUser = User::where('role_id', 4)->where('flag', 0)->first();
        if (!$marketingUser) {
            $marketingUser = $this->createUser(['role_id' => 4, 'name' => $this->prefix . 'Mkt']);
        }

        // Ensure role 4 has 'user' menu permission (id=4 covers 'user/report-list' in CheckPermission::$moduleRouteMap)
        $menuId = (int) (DB::table('menu')->where('routes', 'user')->value('id') ?: 4);
        $existingPerm = DB::table('permission')->where('role_id', 4)->first();
        if ($existingPerm) {
            $menus = json_decode($existingPerm->menu_id, true) ?? [];
            if (!is_array($menus)) {
                $menus = [$menus];
            }
            if (!in_array($menuId, $menus)) {
                $menus[] = $menuId;
                DB::table('permission')->where('role_id', 4)->update(['menu_id' => json_encode($menus)]);
            }
        } else {
            DB::table('permission')->insert([
                'role_id' => 4,
                'menu_id' => json_encode([$menuId]),
                'submenu_id' => json_encode([]),
            ]);
        }

        $this->actingAs($marketingUser);
        $resp = $this->get('/user/report-list?order_category=multiple&search=' . $this->prefix . 'RoleCheckClient');
        $resp->assertOk();
        $resp->assertSee($multiUser->name);
        $resp->assertDontSee('Total Revenue');
        // Masking active for role 4
        $resp->assertDontSee($multiUser->email);
    }

    public function test_live_londonstreet_data_integrity_and_http_responses(): void
    {
        $admin = User::where('role_id', 1)->where('flag', 0)->firstOrFail();
        $this->actingAs($admin);

        // Canonical ground truth SQL directly against live londonstreet data
        $canonicalMultiClients = (int) DB::table(DB::raw("(
            SELECT o.uid
            FROM orders o
            INNER JOIN users u ON u.id = o.uid
            LEFT JOIN leads l ON l.id = o.lead_id AND l.is_converted = 0
            WHERE u.role_id = 2
              AND u.flag = 0
              AND o.uid IS NOT NULL AND o.uid > 0
              AND l.id IS NULL
              AND LOWER(TRIM(COALESCE(o.projectstatus, ''))) NOT IN ('cancelled', 'trash')
              AND (o.order_date IS NOT NULL OR LOWER(TRIM(COALESCE(o.projectstatus, ''))) NOT IN ('', 'pending'))
            GROUP BY o.uid
            HAVING COUNT(*) >= 2
        ) as t"))->count();

        $this->assertGreaterThan(1000, $canonicalMultiClients);

        $scopeCount = User::query()
            ->where('users.flag', 0)
            ->filterByGenuineOrderBounds(Order::resolveClientOrderBounds('multiple'))
            ->count();
        $this->assertSame($canonicalMultiClients, $scopeCount);

        // Verify admin user 12641 (role_id=1) & draft-only user 14295 (if present in live DB) are excluded
        foreach ([12641, 14295] as $excludedId) {
            if (User::find($excludedId)) {
                $found = User::query()
                    ->where('users.id', $excludedId)
                    ->where('users.flag', 0)
                    ->filterByGenuineOrderBounds(Order::resolveClientOrderBounds('multiple'))
                    ->exists();
                $this->assertFalse($found, "Excluded user ID {$excludedId} must NOT appear in multiple genuine orders filter");
            }
        }

        // Endpoint smoke checks on live DB (verifies Blade rendering & query execution time)
        $timings = [];
        foreach ([
            '/user?order_category=multiple',
            '/user?order_category=multiple&scroll=1&page=1',
            '/user',
            '/user?tab=confirmed',
            '/user?tab=not_confirmed',
            '/user?tab=dummy_email',
            '/user/report-list',
            '/user/report-list?order_category=multiple',
            '/orders',
            '/orders?client_orders=multiple',
            '/orders/filter?client_orders=multiple',
            '/orders/filter?status=Delivered',
        ] as $uri) {
            $s = microtime(true);
            $this->get($uri)->assertOk();
            $timings[$uri] = round(microtime(true) - $s, 2);
        }
        $elapsed = array_sum($timings);
        fwrite(STDOUT, "\n[Live Smoke Timings] " . json_encode($timings) . " total=" . round($elapsed, 2) . "s\n");

        $this->assertLessThan(60.0, $elapsed, 'Combined cold-cache live HTTP smoke checks took too long: ' . json_encode($timings));
    }
}

