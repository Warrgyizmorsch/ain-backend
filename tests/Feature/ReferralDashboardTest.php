<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\WalletTransaction;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ReferralDashboardTest extends TestCase
{
    protected string $prefix = 'ZZTEST_REFSA_';

    protected function setUp(): void
    {
        parent::setUp();
        $this->cleanupFixtures();
        DB::beginTransaction();

        $allMenus = DB::table('menu')->pluck('id')->map(fn ($id) => (string) $id)->values()->all();
        $allSubmenus = DB::table('submenus')->pluck('id')->map(fn ($id) => (string) $id)->values()->all();
        foreach ([2, 4, 6, 9] as $roleId) {
            DB::table('permission')->updateOrInsert(
                ['role_id' => $roleId],
                [
                    'menu_id' => json_encode($allMenus),
                    'submenu_id' => json_encode($allSubmenus),
                ]
            );
            Cache::forget('role-allowed-routes-' . $roleId);
        }
    }

    protected function tearDown(): void
    {
        while (DB::transactionLevel() > 0) {
            DB::rollBack();
        }
        $this->cleanupFixtures();
        foreach ([2, 4, 6, 9] as $roleId) {
            Cache::forget('role-allowed-routes-' . $roleId);
        }
        parent::tearDown();
    }

    private function cleanupFixtures(): void
    {
        $userIds = DB::table('users')
            ->where(function ($q) {
                $q->where('name', 'like', $this->prefix . '%')
                    ->orWhere('email', 'like', strtolower($this->prefix) . '%');
            })
            ->pluck('id')
            ->all();

        if (! empty($userIds)) {
            DB::table('wallet_transactions')->whereIn('user_id', $userIds)->delete();
            DB::table('orders')->whereIn('uid', $userIds)->delete();
        }

        DB::table('orders')->where('order_id', 'like', 'ZZREFSA_%')->delete();
        DB::table('users')
            ->where('name', 'like', $this->prefix . '%')
            ->orWhere('email', 'like', strtolower($this->prefix) . '%')
            ->delete();
    }

    private function createUser(int $roleId, array $overrides = []): User
    {
        static $seq = 1;
        $n = $seq++;
        $id = DB::table('users')->insertGetId(array_merge([
            'name' => $this->prefix . 'User_' . $roleId . '_' . $n,
            'email' => strtolower($this->prefix) . $roleId . '_' . $n . '@example.test',
            'countrycode' => '+44',
            'mobile_no' => '799911' . str_pad((string) $n, 4, '0', STR_PAD_LEFT),
            'role_id' => $roleId,
            'flag' => 0,
            'Wallet' => 0.00,
            'total_referral_earnings' => 0.00,
            'password' => Hash::make('secret123'),
            'created_at' => now(),
            'updated_at' => now(),
        ], $overrides));

        return User::findOrFail($id);
    }

    /**
     * Parse UTF-8 CSV response into associative rows keyed by header names.
     *
     * @return array<int, array<string, string>>
     */
    private function parseCsvRows(string $rawCsv): array
    {
        $clean = ltrim($rawCsv, "\xEF\xBB\xBF");
        $stream = fopen('php://temp', 'r+');
        fwrite($stream, $clean);
        rewind($stream);

        $headers = fgetcsv($stream);
        if (! is_array($headers)) {
            fclose($stream);
            return [];
        }

        $rows = [];
        while (($row = fgetcsv($stream)) !== false) {
            if (count($row) === count($headers)) {
                $rows[] = array_combine($headers, $row);
            }
        }
        fclose($stream);

        return $rows;
    }

    public function test_referral_system_sidebar_and_access_are_strictly_restricted_to_super_admin(): void
    {
        $superAdmin = $this->createUser(1);
        $subAdmin = $this->createUser(9);
        $marketing = $this->createUser(4);
        $writerTl = $this->createUser(6);
        $client = $this->createUser(2);

        $this->get('/referral-system')->assertRedirect('/login');
        $this->get('/referral-system/export')->assertRedirect('/login');

        // Super Admin sees sidebar entry & can open page + export
        $profileSa = $this->actingAs($superAdmin)->get('/profile');
        $profileSa->assertStatus(200);
        $profileSa->assertSee('Referral System');
        $profileSa->assertSee('referral-system');

        $this->actingAs($superAdmin)->get('/referral-system')->assertStatus(200);
        $this->actingAs($superAdmin)->get('/referral-system/export')->assertStatus(200);

        // Non-Super Admin roles do NOT see sidebar entry and are denied 403 on both routes
        foreach ([$subAdmin, $marketing, $writerTl, $client] as $other) {
            $resp = $this->actingAs($other)->get('/profile');
            $resp->assertStatus(200);
            $resp->assertDontSee('href="' . url('referral-system') . '"', false);
            $resp->assertDontSee('<span class="menu-title">Referral System</span>', false);

            $this->actingAs($other)->get('/referral-system')->assertStatus(403);
            $this->actingAs($other)->get('/referral-system/export')->assertStatus(403);
        }
    }

    public function test_super_admin_dashboard_data_accuracy_sort_order_filters_and_exports_match(): void
    {
        $superAdmin = $this->createUser(1);
        $exec = $this->createUser(4, [
            'name' => $this->prefix . 'MarketingExec',
            'referral_prefix' => 'Z9Q',
        ]);

        // Uncoded legacy client with many signups (must sort AFTER newly coded client)
        $legacyUncodedReferrer = $this->createUser(2, [
            'name' => $this->prefix . 'LegacyUncoded',
            'referral_code' => null,
            'Wallet' => 'not-a-number',
            'total_referral_earnings' => 0.00,
        ]);

        $referrer = $this->createUser(2, [
            'name' => $this->prefix . 'ReferrerA',
            'referral_code' => 'Z9Q999',
            'referral_discount_percent' => 15.00,
            'referral_executive_id' => $exec->id,
            'referral_prefix' => 'Z9Q',
            'referral_code_generated_at' => now(),
            'referral_updated_by' => $superAdmin->id,
            'Wallet' => '25.00',
            'total_referral_earnings' => 20.00,
        ]);

        $friendWithOrders = $this->createUser(2, [
            'name' => $this->prefix . 'FriendBuyer',
            'refer_id' => $referrer->id,
        ]);

        $friendPending = $this->createUser(2, [
            'name' => $this->prefix . 'FriendPending',
            'refer_id' => $referrer->id,
        ]);

        $friendLegacyOrdered = $this->createUser(2, [
            'name' => $this->prefix . 'FriendLegacyOrdered',
            'refer_id' => $legacyUncodedReferrer->id,
        ]);

        $friendSignupOnly = $this->createUser(2, [
            'name' => $this->prefix . 'FriendSignupOnly',
            'refer_id' => $legacyUncodedReferrer->id,
        ]);

        $today = now()->format('Y-m-d');

        // 1. Awarded order for FriendBuyer
        DB::table('orders')->insert([
            'order_id' => 'ZZREFSA_ORD_1',
            'uid' => $friendWithOrders->id,
            'title' => $this->prefix . 'Awarded Project',
            'amount' => '200.00',
            'received_amount' => '200.00',
            'projectstatus' => 'Ongoing',
            'order_date' => $today,
            'coupon_code' => 'Z9Q999',
            'coupon_discount_type' => 'referral',
            'coupon_discount_value' => 15.00,
            'coupon_discount_amount' => 35.29,
            'coupon_original_amount' => 235.29,
            'referred_by_user_id' => $referrer->id,
            'referral_bonus_awarded_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 2. Cancelled referred order for FriendBuyer
        DB::table('orders')->insert([
            'order_id' => 'ZZREFSA_ORD_2',
            'uid' => $friendWithOrders->id,
            'title' => $this->prefix . 'Cancelled Project',
            'amount' => '100.00',
            'received_amount' => '0',
            'projectstatus' => 'Cancelled',
            'order_date' => $today,
            'coupon_code' => 'Z9Q999',
            'coupon_discount_type' => 'referral',
            'coupon_discount_value' => 15.00,
            'coupon_discount_amount' => 17.65,
            'coupon_original_amount' => 117.65,
            'referred_by_user_id' => $referrer->id,
            'referral_bonus_awarded_at' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 3. Pending bonus conversion order for FriendPending
        DB::table('orders')->insert([
            'order_id' => 'ZZREFSA_ORD_3',
            'uid' => $friendPending->id,
            'title' => $this->prefix . 'Pending Project',
            'amount' => '120.00',
            'received_amount' => '0',
            'projectstatus' => 'Pending',
            'order_date' => null,
            'coupon_code' => 'Z9Q999',
            'coupon_discount_type' => 'referral',
            'coupon_discount_value' => 15.00,
            'coupon_discount_amount' => 21.18,
            'coupon_original_amount' => 141.18,
            'referred_by_user_id' => $referrer->id,
            'referral_bonus_awarded_at' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 4. Genuine legacy order without referral code on FriendLegacyOrdered
        DB::table('orders')->insert([
            'order_id' => 'ZZREFSA_ORD_4',
            'uid' => $friendLegacyOrdered->id,
            'title' => $this->prefix . 'Legacy Direct Project',
            'amount' => '90.00',
            'received_amount' => '90.00',
            'projectstatus' => 'Delivered',
            'order_date' => $today,
            'referred_by_user_id' => null,
            'referral_bonus_awarded_at' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        WalletTransaction::create([
            'user_id' => $referrer->id,
            'amount' => 20.00,
            'type' => 'credit',
            'description' => '=10% Referral bonus from ' . $friendWithOrders->name . ' (Order #ZZREFSA_ORD_1)',
            'balance_after' => 25.00,
        ]);

        // Tab 1: Clients & Codes + exact sort order + view data
        $clientsRes = $this->actingAs($superAdmin)->get('/referral-system?tab=clients&search=' . $this->prefix);
        $clientsRes->assertStatus(200);
        $clientsPaginator = $clientsRes->viewData('clients');
        $this->assertNotNull($clientsPaginator);
        $clientIds = collect($clientsPaginator->items())->pluck('id')->all();
        $this->assertSame([$referrer->id, $legacyUncodedReferrer->id], $clientIds);
        $clientsRes->assertSee('Z9Q999');
        $clientsRes->assertSee('15% OFF');
        $clientsRes->assertSee($this->prefix . 'MarketingExec');

        // Verify TabCounts remain accurate on inactive tabs when Clients has status filter
        $filteredClientsRes = $this->actingAs($superAdmin)->get('/referral-system?tab=clients&search=' . $this->prefix . '&status=custom_discount');
        $filteredClientsRes->assertStatus(200);
        $tabCounts = $filteredClientsRes->viewData('tabCounts');
        $this->assertSame(1, $tabCounts['clients']);
        $this->assertSame(3, $tabCounts['orders']);

        // Tab 2: Referred Orders (UI & exact counts)
        $ordersRes = $this->actingAs($superAdmin)->get('/referral-system?tab=orders&search=ZZREFSA_ORD_&from_date=' . $today . '&to_date=' . $today);
        $ordersRes->assertStatus(200);
        $ordersRes->assertSee('ZZREFSA_ORD_1');
        $ordersRes->assertSee('Bonus Awarded');
        $ordersRes->assertSee('ZZREFSA_ORD_2');
        $ordersRes->assertSee('Not Eligible (Cancelled)');
        $ordersRes->assertSee('ZZREFSA_ORD_3');
        $ordersRes->assertSee('Pending Confirmation');

        // Tab 3: Referred Signups & lifecycle status distinction
        $signupsRes = $this->actingAs($superAdmin)->get('/referral-system?tab=signups&search=' . $this->prefix);
        $signupsRes->assertStatus(200);
        $signupsRes->assertSee('Bonus Unlocked (10%)');
        $signupsRes->assertSee('Pending Bonus Conversion');
        $signupsRes->assertSee('Ordered (No Code / Legacy)');
        $signupsRes->assertSee('Signed Up Only (No Order)');

        $legacyOnlyRes = $this->actingAs($superAdmin)->get('/referral-system?tab=signups&search=' . $this->prefix . '&status=legacy_ordered');
        $legacyOnlyRes->assertStatus(200);
        $legacyOnlyRes->assertSee($this->prefix . 'FriendLegacyOrdered');
        $legacyOnlyRes->assertDontSee($this->prefix . 'FriendBuyer');
        $legacyOnlyRes->assertDontSee($this->prefix . 'FriendSignupOnly');

        // Tab 4: Executives Performance (including date filter support)
        $execRes = $this->actingAs($superAdmin)->get('/referral-system?tab=executives&search=Z9Q&from_date=' . $today . '&to_date=' . $today);
        $execRes->assertStatus(200);
        $execRows = $execRes->viewData('executives');
        $this->assertCount(1, $execRows);
        $this->assertSame(1, $execRows[0]->coded_clients_count);
        $this->assertSame(2, $execRows[0]->referred_signups_count);
        $this->assertSame(3, $execRows[0]->referred_orders_count);
        $this->assertEqualsWithDelta(320.00, $execRows[0]->referred_revenue, 0.01);
        $this->assertEqualsWithDelta(56.47, $execRows[0]->discount_given, 0.01);
        $this->assertEqualsWithDelta(20.00, $execRows[0]->bonuses_unlocked, 0.01);

        // Tab 5: Wallet Bonus Ledger
        $walletRes = $this->actingAs($superAdmin)->get('/referral-system?tab=wallet&search=ZZREFSA_ORD_1&from_date=' . $today . '&to_date=' . $today);
        $walletRes->assertStatus(200);
        $walletRes->assertSee('ZZREFSA_ORD_1');
        $walletRes->assertSee($this->prefix . 'ReferrerA');

        // Strict CSV Export verification across ALL 5 tabs (1-to-1 field accuracy & formula safety)
        $clientsCsv = $this->parseCsvRows(
            $this->actingAs($superAdmin)->get('/referral-system/export?tab=clients&search=' . $this->prefix)->streamedContent()
        );
        $this->assertCount(2, $clientsCsv);
        $this->assertSame($this->prefix . 'ReferrerA', $clientsCsv[0]['Client Name']);
        $this->assertSame('Z9Q999', $clientsCsv[0]['Referral Code']);
        $this->assertSame('15.00', $clientsCsv[0]['Discount %']);
        $this->assertSame('2', $clientsCsv[0]['Referred Signups']);
        $this->assertSame('3', $clientsCsv[0]['Referred Orders']);
        $this->assertSame('1', $clientsCsv[0]['Bonus Orders']);
        $this->assertSame('320.00', $clientsCsv[0]['Referred Revenue (GBP)']);
        $this->assertSame('56.47', $clientsCsv[0]['Discount Given (GBP)']);
        $this->assertSame('20.00', $clientsCsv[0]['Total Referral Earnings (GBP)']);
        $this->assertSame('25.00', $clientsCsv[0]['Current Wallet (GBP)']);
        $this->assertSame('0.00', $clientsCsv[1]['Current Wallet (GBP)']);

        $ordersCsv = $this->parseCsvRows(
            $this->actingAs($superAdmin)->get('/referral-system/export?tab=orders&search=ZZREFSA_ORD_')->streamedContent()
        );
        $this->assertCount(3, $ordersCsv);
        $ordersByCode = collect($ordersCsv)->keyBy('Order Code');
        $this->assertSame('Bonus Awarded', $ordersByCode['ZZREFSA_ORD_1']['Bonus Status']);
        $this->assertSame('20.00', $ordersByCode['ZZREFSA_ORD_1']['Bonus Amount (GBP)']);
        $this->assertSame('Not Eligible (Cancelled)', $ordersByCode['ZZREFSA_ORD_2']['Bonus Status']);
        $this->assertSame('Pending Confirmation', $ordersByCode['ZZREFSA_ORD_3']['Bonus Status']);

        $signupsCsv = $this->parseCsvRows(
            $this->actingAs($superAdmin)->get('/referral-system/export?tab=signups&search=' . $this->prefix)->streamedContent()
        );
        $this->assertCount(4, $signupsCsv);
        $signupsByName = collect($signupsCsv)->keyBy('Referred Client Name');
        $this->assertSame('Bonus Unlocked (10%)', $signupsByName[$this->prefix . 'FriendBuyer']['Lifecycle Status']);
        $this->assertSame('Pending Bonus Conversion', $signupsByName[$this->prefix . 'FriendPending']['Lifecycle Status']);
        $this->assertSame('Ordered (No Code / Legacy)', $signupsByName[$this->prefix . 'FriendLegacyOrdered']['Lifecycle Status']);
        $this->assertSame('Signed Up Only (No Order)', $signupsByName[$this->prefix . 'FriendSignupOnly']['Lifecycle Status']);

        $execCsv = $this->parseCsvRows(
            $this->actingAs($superAdmin)->get('/referral-system/export?tab=executives&search=Z9Q')->streamedContent()
        );
        $this->assertCount(1, $execCsv);
        $this->assertSame('Z9Q', $execCsv[0]['3-Char Prefix']);
        $this->assertSame('320.00', $execCsv[0]['Referred Revenue (GBP)']);
        $this->assertSame('20.00', $execCsv[0]['Bonuses Unlocked (GBP)']);

        $walletCsv = $this->parseCsvRows(
            $this->actingAs($superAdmin)->get('/referral-system/export?tab=wallet&search=ZZREFSA_ORD_1')->streamedContent()
        );
        $this->assertCount(1, $walletCsv);
        $this->assertStringStartsWith("'=10% Referral bonus", $walletCsv[0]['Description']);
        $this->assertSame('Z9Q999', $walletCsv[0]['Referral Code']);
        $this->assertSame('25.00', $walletCsv[0]['Current Wallet (GBP)']);

        // Inline update endpoint works & reflects cleanly on dashboard
        $updateRes = $this->actingAs($superAdmin)->postJson(route('user.referral.update', $referrer->id), [
            'referral_code' => 'Z9Q888',
            'referral_discount_percent' => '20.00',
            'referral_executive_id' => $exec->id,
        ]);
        $updateRes->assertStatus(200)->assertJson(['success' => true]);

        $reloaded = $this->actingAs($superAdmin)->get('/referral-system?tab=clients&search=Z9Q888');
        $reloaded->assertStatus(200);
        $reloaded->assertSee('Z9Q888');
        $reloaded->assertSee('20% OFF');
    }

    public function test_legacy_referral_cleanup_migration_clears_only_unused_old_ref_codes(): void
    {
        $unusedLegacy = $this->createUser(2, [
            'name' => $this->prefix . 'UnusedLegacy',
            'referral_code' => 'REFZZZ111',
            'referral_code_generated_at' => null,
            'referral_executive_id' => null,
            'referral_prefix' => null,
            'referral_updated_by' => null,
            'total_referral_earnings' => 0.00,
        ]);

        $usedBySignup = $this->createUser(2, [
            'name' => $this->prefix . 'UsedLegacySignup',
            'referral_code' => 'REFZZZ222',
            'referral_code_generated_at' => null,
            'total_referral_earnings' => 0.00,
        ]);
        $this->createUser(2, [
            'name' => $this->prefix . 'ChildOfUsedLegacy',
            'refer_id' => $usedBySignup->id,
        ]);

        $usedByCouponOrder = $this->createUser(2, [
            'name' => $this->prefix . 'UsedLegacyOrder',
            'referral_code' => 'REFZZZ333',
            'referral_code_generated_at' => null,
            'total_referral_earnings' => 0.00,
        ]);
        $buyer = $this->createUser(2, ['name' => $this->prefix . 'BuyerForLegacy']);
        DB::table('orders')->insert([
            'order_id' => 'ZZREFSA_LEG_1',
            'uid' => $buyer->id,
            'title' => $this->prefix . 'Legacy Order',
            'amount' => '50.00',
            'coupon_code' => 'REFZZZ333',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $newPatternClient = $this->createUser(2, [
            'name' => $this->prefix . 'NewPattern',
            'referral_code' => 'USK49M',
            'referral_prefix' => 'USK',
            'referral_code_generated_at' => now(),
        ]);

        $migration = require database_path('migrations/2026_11_12_121000_clear_unused_legacy_referral_codes.php');
        $migration->up();

        $this->assertNull(User::findOrFail($unusedLegacy->id)->referral_code);
        $this->assertSame('REFZZZ222', User::findOrFail($usedBySignup->id)->referral_code);
        $this->assertSame('REFZZZ333', User::findOrFail($usedByCouponOrder->id)->referral_code);
        $this->assertSame('USK49M', User::findOrFail($newPatternClient->id)->referral_code);
    }
}
