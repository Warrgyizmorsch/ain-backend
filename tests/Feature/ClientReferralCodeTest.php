<?php

namespace Tests\Feature;

use App\Models\Leads;
use App\Models\Order;
use App\Models\User;
use App\Models\WalletTransaction;
use App\Services\ReferralCodeService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ClientReferralCodeTest extends TestCase
{
    protected string $prefix = 'ZZTEST_REF_';

    protected function setUp(): void
    {
        parent::setUp();
        Mail::fake();
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
        $userIds = DB::table('users')
            ->where(function ($q) {
                $q->where('name', 'like', $this->prefix . '%')
                    ->orWhere('email', 'like', strtolower($this->prefix) . '%');
            })
            ->pluck('id')
            ->all();

        if (!empty($userIds)) {
            DB::table('wallet_transactions')->whereIn('user_id', $userIds)->delete();
            DB::table('coupon_usages')->whereIn('user_id', $userIds)->delete();
            DB::table('orders')->whereIn('uid', $userIds)->delete();
            DB::table('leads')->whereIn('emp_id', $userIds)->delete();
        }

        DB::table('orders')->where('title', 'like', $this->prefix . '%')->delete();
        DB::table('leads')
            ->where('user_name', 'like', $this->prefix . '%')
            ->orWhere('order_id', 'like', 'ZZREF_%')
            ->delete();
        DB::table('coupons')->where('coupon_code', 'like', 'ZZREF%')->delete();
        DB::table('users')
            ->where('name', 'like', $this->prefix . '%')
            ->orWhere('email', 'like', strtolower($this->prefix) . '%')
            ->delete();
    }

    private function createUser(array $overrides = []): User
    {
        static $seq = 1;
        $n = $seq++;
        $id = DB::table('users')->insertGetId(array_merge([
            'name' => $this->prefix . 'User_' . $n,
            'email' => strtolower($this->prefix) . 'u' . $n . '@example.test',
            'countrycode' => '44',
            'mobile_no' => '792200' . str_pad((string) $n, 4, '0', STR_PAD_LEFT),
            'role_id' => 2,
            'flag' => 0,
            'Wallet' => 0.00,
            'total_referral_earnings' => 0.00,
            'password' => Hash::make('secret123'),
            'created_at' => now(),
            'updated_at' => now(),
        ], $overrides));

        return User::findOrFail($id);
    }

    private function grantTestRoutesForRoles(): void
    {
        foreach ([1, 4, 6, 9] as $roleId) {
            Cache::put('role-allowed-routes-' . $roleId, ['user', 'orders', 'lead'], now()->addMinutes(5));
        }
    }

    public function test_executive_prefix_derivation_and_uniqueness_across_staff(): void
    {
        $service = app(ReferralCodeService::class);

        $exec1 = $this->createUser([
            'name' => 'Usk Kelvin',
            'email' => strtolower($this->prefix) . 'exec_usk1@example.test',
            'role_id' => 4,
        ]);
        $exec2 = $this->createUser([
            'name' => 'Usk Kiran',
            'email' => strtolower($this->prefix) . 'exec_usk2@example.test',
            'role_id' => 4,
        ]);

        $candidates1 = $service->derivePrefixCandidates('Usk Kelvin');
        $this->assertSame('US', $candidates1[0]);

        $prefix1 = $service->ensureExecutivePrefix($exec1);
        $prefix2 = $service->ensureExecutivePrefix($exec2);

        $this->assertMatchesRegularExpression('/^[A-Z0-9]{2}$/', $prefix1);
        $this->assertMatchesRegularExpression('/^[A-Z0-9]{2}$/', $prefix2);
        $this->assertNotSame($prefix1, $prefix2);
    }

    public function test_no_referral_code_generated_on_signup_refer_list_unconverted_cancelled_or_pending(): void
    {
        // 1. Mobile app registration does NOT generate referral_code
        $regEmail = strtolower($this->prefix) . 'signup1@example.test';
        $regResp = $this->postJson('/api/app/register', [
            'name' => $this->prefix . 'SignupOnly',
            'email' => $regEmail,
            'phone_no' => '7922110001',
            'password' => 'Pass@12345',
            'confirm_password' => 'Pass@12345',
        ]);
        $regResp->assertStatus(201)
            ->assertJsonPath('success', true);
        $signedUp = User::where('email', $regEmail)->firstOrFail();
        $this->assertNull($signedUp->referral_code);
        $this->assertNull($signedUp->referral_code_generated_at);

        // 2. /api/app/refer-list does NOT auto-generate referral_code before conversion
        Sanctum::actingAs($signedUp);
        $referListResp = $this->getJson('/api/app/refer-list');
        $referListResp->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonPath('has_referral_code', false)
            ->assertJsonPath('referral_code', null);

        $signedUp->refresh();
        $this->assertNull($signedUp->referral_code);

        // 3. Unconverted Lead + linked Order (even with order_date set) must NOT generate code
        $lead = Leads::create([
            'order_id' => 'ZZREF_UNCONV_' . rand(1000, 9999),
            'emp_id' => $signedUp->id,
            'user_name' => $signedUp->name,
            'email' => $signedUp->email,
            'countrycode' => '44',
            'mobile' => $signedUp->mobile_no,
            'project_title' => $this->prefix . 'Unconverted Lead',
            'price' => 150,
            'is_converted' => 0,
            'duplicate_lead' => 0,
        ]);
        Order::create([
            'uid' => $signedUp->id,
            'order_id' => $lead->order_id,
            'lead_id' => $lead->id,
            'title' => $this->prefix . 'Unconverted Order',
            'amount' => 150,
            'order_date' => Carbon::now()->toDateString(),
            'projectstatus' => 'Other',
        ]);
        $this->assertNull($signedUp->fresh()->referral_code);

        // 4. Undated Pending order (e.g., app draft) must NOT generate code
        Order::create([
            'uid' => $signedUp->id,
            'order_id' => 'ZZREF_PEND_' . rand(1000, 9999),
            'title' => $this->prefix . 'Pending Draft Order',
            'amount' => 180,
            'order_date' => null,
            'projectstatus' => 'Pending',
        ]);
        $this->assertNull($signedUp->fresh()->referral_code);

        // 5. Cancelled / Trash order with order_date must NOT generate code
        Order::create([
            'uid' => $signedUp->id,
            'order_id' => 'ZZREF_CANC_' . rand(1000, 9999),
            'title' => $this->prefix . 'Cancelled Order',
            'amount' => 200,
            'order_date' => Carbon::now()->toDateString(),
            'projectstatus' => 'Cancelled',
        ]);
        Order::create([
            'uid' => $signedUp->id,
            'order_id' => 'ZZREF_TRSH_' . rand(1000, 9999),
            'title' => $this->prefix . 'Trash Order',
            'amount' => 200,
            'order_date' => Carbon::now()->toDateString(),
            'projectstatus' => 'Trash',
        ]);
        $this->assertNull($signedUp->fresh()->referral_code);

        // 6. Staff (role_id != 2) & soft-deleted users (flag = 1) never receive client referral codes on orders
        $staff = $this->createUser(['role_id' => 4, 'referral_prefix' => 'ZZ9']);
        $deletedClient = $this->createUser(['role_id' => 2, 'flag' => 1]);
        foreach ([$staff, $deletedClient] as $excludedUser) {
            Order::create([
                'uid' => $excludedUser->id,
                'order_id' => 'ZZREF_EXCL_' . rand(10000, 99999),
                'title' => $this->prefix . 'Excluded Order',
                'amount' => 100,
                'order_date' => Carbon::now()->toDateString(),
                'projectstatus' => 'Delivered',
            ]);
            $this->assertNull($excludedUser->fresh()->referral_code);
        }
    }

    public function test_converted_order_generates_user3_exec2_discount10_code_and_preserves_on_repeat_orders(): void
    {
        $exec = $this->createUser([
            'name' => $this->prefix . 'ExecUsk',
            'email' => strtolower($this->prefix) . 'exec_z1@example.test',
            'role_id' => 4,
            'referral_prefix' => 'Z1',
        ]);
        $client = $this->createUser(['name' => $this->prefix . 'GenuineClient']);

        // Create unconverted lead + order first
        $lead = Leads::create([
            'order_id' => 'ZZREF_CONV_' . rand(10000, 99999),
            'emp_id' => $client->id,
            'user_name' => $client->name,
            'email' => $client->email,
            'countrycode' => '44',
            'mobile' => $client->mobile_no,
            'project_title' => $this->prefix . 'Thesis Project',
            'price' => 300,
            'is_converted' => 0,
            'duplicate_lead' => 0,
            'created_by' => $exec->id,
        ]);
        $order = Order::create([
            'uid' => $client->id,
            'order_id' => $lead->order_id,
            'lead_id' => $lead->id,
            'title' => $this->prefix . 'Thesis Order',
            'amount' => 300,
            'order_date' => Carbon::now()->toDateString(),
            'projectstatus' => 'Other',
            'created_by' => $exec->id,
            'l_converted_by' => $exec->name,
        ]);
        $this->assertNull($client->fresh()->referral_code);

        // Convert the lead (simulates CRM lead conversion)
        $this->actingAs($exec);
        $lead->is_converted = 1;
        $lead->converted_at = now();
        $lead->save();

        $client->refresh();
        $this->assertNotNull($client->referral_code);
        $this->assertSame('ZZTZ110', $client->referral_code);
        $this->assertMatchesRegularExpression('/^[A-Z0-9]{3}Z110$/', $client->referral_code);
        $this->assertSame('10.00', number_format((float) $client->referral_discount_percent, 2, '.', ''));
        $this->assertSame((int) $exec->id, (int) $client->referral_executive_id);
        $this->assertSame('Z1', $client->referral_prefix);
        $this->assertNotNull($client->referral_code_generated_at);

        $originalCode = $client->referral_code;

        // Admin customizes discount to 15%
        $client->referral_discount_percent = 15.00;
        $client->saveQuietly();

        // Second converted order must keep original code & 15% discount untouched
        $exec2 = $this->createUser([
            'name' => $this->prefix . 'ExecTwo',
            'email' => strtolower($this->prefix) . 'exec_z2@example.test',
            'role_id' => 4,
            'referral_prefix' => 'Z2',
        ]);
        $lead2 = Leads::create([
            'order_id' => 'ZZREF_CONV2_' . rand(10000, 99999),
            'emp_id' => $client->id,
            'user_name' => $client->name,
            'email' => $client->email,
            'countrycode' => '44',
            'mobile' => $client->mobile_no,
            'project_title' => $this->prefix . 'Repeat Project',
            'price' => 250,
            'is_converted' => 1,
            'converted_at' => now(),
            'duplicate_lead' => 0,
            'created_by' => $exec2->id,
        ]);
        Order::create([
            'uid' => $client->id,
            'order_id' => $lead2->order_id,
            'lead_id' => $lead2->id,
            'title' => $this->prefix . 'Repeat Order',
            'amount' => 250,
            'order_date' => Carbon::now()->toDateString(),
            'projectstatus' => 'Delivered',
            'created_by' => $exec2->id,
            'l_converted_by' => $exec2->name,
        ]);

        $client->refresh();
        $this->assertSame($originalCode, $client->referral_code);
        $this->assertSame('15.00', number_format((float) $client->referral_discount_percent, 2, '.', ''));
        $this->assertSame((int) $exec->id, (int) $client->referral_executive_id);
    }

    public function test_global_uniqueness_across_users_and_coupons_tables(): void
    {
        $service = app(ReferralCodeService::class);

        $existingCouponCode = 'ZZREFCPN9';
        DB::table('coupons')->insert([
            'coupon_code' => $existingCouponCode,
            'discount_type' => 'percentage',
            'discount_value' => 10,
            'is_active' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->assertTrue($service->codeExistsAnywhere('zzrefcpn9'));

        $admin = $this->createUser(['role_id' => 1]);
        $client1 = $this->createUser(['referral_code' => 'Z9C234', 'referral_discount_percent' => 10.00]);
        $client2 = $this->createUser(['referral_code' => 'Z9C567', 'referral_discount_percent' => 10.00]);

        $this->grantTestRoutesForRoles();
        $this->actingAs($admin);

        // Collision with coupons table rejected case-insensitively
        $respCouponCollide = $this->postJson(route('user.referral.update', $client2->id), [
            'referral_code' => 'zzrefcpn9',
            'referral_discount_percent' => 12,
        ]);
        $respCouponCollide->assertStatus(422)
            ->assertJsonPath('success', false);

        // Collision with another user's referral_code rejected case-insensitively
        $respUserCollide = $this->postJson(route('user.referral.update', $client2->id), [
            'referral_code' => 'z9c234',
            'referral_discount_percent' => 12,
        ]);
        $respUserCollide->assertStatus(422)
            ->assertJsonPath('success', false);

        // Updating self with own existing code + new % succeeds
        $respSelfUpdate = $this->postJson(route('user.referral.update', $client1->id), [
            'referral_code' => 'Z9C234',
            'referral_discount_percent' => 18.5,
        ]);
        $respSelfUpdate->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.referral_code', 'Z9C234')
            ->assertJsonPath('data.referral_discount_display', '18.5');
    }

    public function test_double_sided_referral_checkout_and_single_idempotent_wallet_bonus(): void
    {
        $exec = $this->createUser([
            'name' => $this->prefix . 'ExecPiy',
            'email' => strtolower($this->prefix) . 'exec_p9@example.test',
            'role_id' => 4,
            'referral_prefix' => 'P9',
        ]);

        $referrer = $this->createUser([
            'name' => $this->prefix . 'ReferrerA',
            'referral_code' => 'Z9A49M',
            'referral_discount_percent' => 15.00,
            'referral_executive_id' => $exec->id,
            'referral_prefix' => 'Z9A',
            'referral_code_generated_at' => now()->subDays(5),
            'Wallet' => 0.00,
            'total_referral_earnings' => 0.00,
        ]);

        $friend = $this->createUser([
            'name' => $this->prefix . 'FriendB',
            'Wallet' => 0.00,
        ]);

        // 1. Self-referral rejected
        $selfResp = $this->postJson('/api/app/apply-coupon', [
            'coupon_code' => 'Z9A49M',
            'order_amount' => 200,
            'user_id' => $referrer->id,
        ]);
        $selfResp->assertStatus(422)
            ->assertJsonPath('success', false);

        // 2. Friend validates referral code via /api/app/apply-coupon -> gets referrer's custom 15% discount
        $applyResp = $this->postJson('/api/app/apply-coupon', [
            'coupon_code' => 'z9a49m',
            'order_amount' => 200,
            'user_id' => $friend->id,
        ]);
        $applyResp->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonPath('is_referral', true)
            ->assertJsonPath('data.discount_value', 15)
            ->assertJsonPath('discount_amount', 30)
            ->assertJsonPath('final_payable_amount', 170);

        // 3. Friend places order via Sanctum /api/app/place-order (even passing stale finalPrice=200 from client)
        Sanctum::actingAs($friend);
        $placeResp = $this->postJson('/api/app/place-order', [
            'country' => 'UK',
            'countryCode' => '44',
            'phoneNumber' => $friend->mobile_no,
            'service' => $this->prefix . 'FriendEssay',
            'workType' => 'Standard',
            'subject' => 'CS101',
            'urgency' => '7',
            'wordCount' => 2000,
            'topic' => $this->prefix . 'FriendEssayOrder',
            'requirements' => 'Test referral discount flow',
            'order_amount' => 200,
            'finalPrice' => 200,
            'coupon_code' => 'z9a49m',
            'delivery_date' => Carbon::now()->addDays(7)->toDateString(),
        ]);
        $placeResp->assertStatus(201)
            ->assertJsonPath('success', true)
            ->assertJsonPath('coupon_discount_amount', 30)
            ->assertJsonPath('total_amount', 170)
            ->assertJsonPath('first_order_referral_bonus_awarded', false);

        $friend->refresh();
        $referrer->refresh();

        $this->assertSame((int) $referrer->id, (int) $friend->refer_id);
        // Order is still Pending -> Friend must NOT get own referral code yet, and Referrer wallet must still be 0
        $this->assertNull($friend->referral_code);
        $this->assertEquals(0.0, (float) $referrer->Wallet);

        $friendOrder = Order::where('uid', $friend->id)->latest('id')->firstOrFail();
        $friendLead = Leads::findOrFail($friendOrder->lead_id);
        $this->assertEquals(170.0, (float) $friendOrder->amount);
        $this->assertEquals(30.0, (float) $friendOrder->coupon_discount_amount);
        $this->assertSame('Z9A49M', $friendOrder->coupon_code);
        $this->assertSame((int) $referrer->id, (int) $friendOrder->referred_by_user_id);
        $this->assertNull($friendOrder->referral_bonus_awarded_at);

        // 4. Repeat referral discount blocked for Friend B
        $repeatApplyResp = $this->postJson('/api/app/apply-coupon', [
            'coupon_code' => 'Z9A49M',
            'order_amount' => 300,
            'user_id' => $friend->id,
        ]);
        $repeatApplyResp->assertStatus(422)
            ->assertJsonPath('success', false);

        // 5. Convert Friend B's order -> B gets 6-char referral code + Referrer A gets 10% Wallet bonus (17.00) strictly once
        $this->actingAs($exec);
        $friendOrder->order_date = Carbon::now()->toDateString();
        $friendOrder->projectstatus = 'Other';
        $friendOrder->l_converted_by = $exec->name;
        $friendOrder->save();

        $friendLead->is_converted = 1;
        $friendLead->converted_at = now();
        $friendLead->save();

        $friend->refresh();
        $referrer->refresh();
        $friendOrder->refresh();

        $this->assertNotNull($friend->referral_code);
        $this->assertSame('ZZTP910', $friend->referral_code);
        $this->assertMatchesRegularExpression('/^[A-Z0-9]{3}[A-Z0-9]{2}10$/', $friend->referral_code);
        $this->assertSame('P9', $friend->referral_prefix);
        $this->assertSame('10.00', number_format((float) $friend->referral_discount_percent, 2, '.', ''));

        $this->assertNotNull($friendOrder->referral_bonus_awarded_at);
        $this->assertEquals(17.00, (float) $referrer->Wallet);
        $this->assertEquals(17.00, (float) $referrer->total_referral_earnings);
        $this->assertSame(1, WalletTransaction::where('user_id', $referrer->id)->where('type', 'credit')->count());

        // 6. Idempotency: repeated saves or second order from Friend B MUST NOT award a second bonus
        $friendOrder->title = $this->prefix . 'UpdatedAfterConversion';
        $friendOrder->save();
        $this->assertFalse(app(ReferralCodeService::class)->awardReferrerBonusIfEligible($friendOrder, $friend));

        Order::create([
            'uid' => $friend->id,
            'order_id' => 'ZZREF_SECOND_' . rand(1000, 9999),
            'title' => $this->prefix . 'FriendSecondOrder',
            'amount' => 500,
            'order_date' => Carbon::now()->toDateString(),
            'projectstatus' => 'Delivered',
            'referred_by_user_id' => $referrer->id,
        ]);

        $referrer->refresh();
        $this->assertEquals(17.00, (float) $referrer->Wallet);
        $this->assertSame(1, WalletTransaction::where('user_id', $referrer->id)->where('type', 'credit')->count());

        // 7. Referrer's /api/app/refer-list reflects their code + credited bonus accurately
        Sanctum::actingAs($referrer);
        $referrerList = $this->getJson('/api/app/refer-list');
        $referrerList->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonPath('has_referral_code', true)
            ->assertJsonPath('referral_code', 'Z9A49M')
            ->assertJsonPath('referral_discount_percent', 15)
            ->assertJsonPath('data.0.has_ordered', true)
            ->assertJsonPath('data.0.referral_bonus_earned', 17);
    }

    public function test_rbac_ui_visibility_editing_permissions_and_search_surfaces(): void
    {
        $this->grantTestRoutesForRoles();

        $admin = $this->createUser(['role_id' => 1, 'name' => $this->prefix . 'SuperAdmin', 'referral_prefix' => 'A1']);
        $subAdmin = $this->createUser(['role_id' => 9, 'name' => $this->prefix . 'SubAdmin', 'referral_prefix' => 'S9']);
        $marketing = $this->createUser(['role_id' => 4, 'name' => $this->prefix . 'MarketingExec', 'referral_prefix' => 'M4']);
        $writerTl = $this->createUser(['role_id' => 6, 'name' => $this->prefix . 'WriterTL']);

        $client = $this->createUser([
            'name' => $this->prefix . 'VisibleClient',
            'referral_code' => 'ZZTM410',
            'referral_discount_percent' => 12.50,
            'referral_executive_id' => $marketing->id,
            'referral_prefix' => 'M4',
            'referral_code_generated_at' => now(),
        ]);

        $order = Order::create([
            'uid' => $client->id,
            'order_id' => 'ZZREF_ORD_' . rand(10000, 99999),
            'title' => $this->prefix . 'RBAC Order',
            'amount' => 100,
            'order_date' => Carbon::now()->toDateString(),
            'projectstatus' => 'Delivered',
        ]);

        // 1. /user view & edit visibility per role + lazy executives endpoint RBAC
        $this->actingAs($admin);
        $adminHtml = $this->get('/user?user_id=' . $client->id)->assertStatus(200)->getContent();
        $this->assertStringContainsString('ZZTM410', $adminHtml);
        $this->assertStringContainsString('12.5% OFF', $adminHtml);
        $this->assertStringContainsString('id="editReferralModal"', $adminHtml);
        $this->assertStringContainsString('js-edit-referral-btn', $adminHtml);

        $this->getJson(route('user.referral.executives'))
            ->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonFragment(['id' => $marketing->id, 'referral_prefix' => 'M4']);

        $adminScrollJson = $this->getJson('/user?scroll=1&user_id=' . $client->id)->assertStatus(200)->json('html');
        $this->assertStringContainsString('ZZTM410', (string) $adminScrollJson);
        $this->assertStringContainsString('js-edit-referral-btn', (string) $adminScrollJson);

        $this->actingAs($subAdmin);
        $subAdminHtml = $this->get('/user?user_id=' . $client->id)->assertStatus(200)->getContent();
        $this->assertStringContainsString('ZZTM410', $subAdminHtml);
        $this->assertStringContainsString('id="editReferralModal"', $subAdminHtml);
        $this->assertStringContainsString('js-edit-referral-btn', $subAdminHtml);

        $this->getJson(route('user.referral.executives'))
            ->assertStatus(200)
            ->assertJsonPath('success', true);

        $this->actingAs($marketing);
        $marketingHtml = $this->get('/user?user_id=' . $client->id)->assertStatus(200)->getContent();
        $this->assertStringContainsString('ZZTM410', $marketingHtml);
        $this->assertStringContainsString('12.5% OFF', $marketingHtml);
        $this->assertStringNotContainsString('id="editReferralModal"', $marketingHtml);
        $this->assertStringNotContainsString('js-edit-referral-btn', $marketingHtml);
        $this->getJson(route('user.referral.executives'))->assertStatus(403);

        $this->actingAs($writerTl);
        $writerHtml = $this->get('/user?user_id=' . $client->id)->assertStatus(200)->getContent();
        $this->assertStringNotContainsString('ZZTM410', $writerHtml);
        $this->assertStringNotContainsString('<th class="min-w-170px col-referral">', $writerHtml);
        $this->assertStringNotContainsString('data-user-referral-cell="' . $client->id . '"', $writerHtml);
        $this->assertStringNotContainsString('id="editReferralModal"', $writerHtml);
        $this->getJson(route('user.referral.executives'))->assertStatus(403);

        $writerScrollJson = $this->getJson('/user?scroll=1&user_id=' . $client->id)->assertStatus(200)->json('html');
        $this->assertStringNotContainsString('ZZTM410', (string) $writerScrollJson);
        $this->assertStringNotContainsString('data-user-referral-cell="' . $client->id . '"', (string) $writerScrollJson);

        // 2. /user/report/{id} visibility & edit form RBAC
        $this->actingAs($marketing);
        $mktProfile = $this->get(route('user.report', $client->id))->assertStatus(200)->getContent();
        $this->assertStringContainsString('ZZTM410', $mktProfile);
        $this->assertStringNotContainsString('Save Referral', $mktProfile);

        $this->actingAs($writerTl);
        $writerProfile = $this->get(route('user.report', $client->id))->assertStatus(200)->getContent();
        $this->assertStringNotContainsString('ZZTM410', $writerProfile);
        $this->assertStringNotContainsString('Referral Program Details', $writerProfile);

        // 3. /orders page visibility per role (end-to-end OrderController + row partial)
        $this->actingAs($marketing);
        $mktOrderHtml = $this->get('/orders?uid=' . $client->id)->assertStatus(200)->getContent();
        $this->assertStringContainsString('Ref: ZZTM410 (12.5%)', $mktOrderHtml);

        $this->actingAs($writerTl);
        $writerOrderHtml = $this->get('/orders?uid=' . $client->id)->assertStatus(200)->getContent();
        $this->assertStringNotContainsString('ZZTM410', $writerOrderHtml);

        // 4. Write lock: Marketing (role 4) and WriterTL (role 6) receive 403 on referral edits
        $this->actingAs($marketing);
        $this->postJson(route('user.referral.update', $client->id), [
            'referral_code' => 'HACK99',
            'referral_discount_percent' => 50,
        ])->assertStatus(403);

        $this->postJson(route('user.update', $client->id), [
            'name' => $client->name,
            'email' => $client->email,
            'role' => 2,
            'referral_discount_percent' => 75,
        ])->assertStatus(403);

        $this->actingAs($writerTl);
        $this->postJson(route('user.referral.update', $client->id), [
            'referral_discount_percent' => 50,
        ])->assertStatus(403);

        // Confirm DB remained intact
        $client->refresh();
        $this->assertSame('ZZTM410', $client->referral_code);
        $this->assertSame('12.50', number_format((float) $client->referral_discount_percent, 2, '.', ''));

        // 5. Sub Admin (role 9) CAN update referral code, discount, and executive prefix
        $this->actingAs($subAdmin);
        $this->postJson(route('user.referral.update', $client->id), [
            'referral_code' => 'Vism410',
            'referral_discount_percent' => 20,
            'referral_executive_id' => $marketing->id,
        ])->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.referral_code', 'VISM410');

        $this->postJson(route('user.referral.update', $marketing->id), [
            'referral_prefix' => 'MK',
        ])->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.referral_prefix', 'MK');

        // Reject invalid prefix length (!= 2) and out-of-range discount (> 100)
        $this->postJson(route('user.referral.update', $marketing->id), [
            'referral_prefix' => 'ABC',
        ])->assertStatus(422);

        $this->postJson(route('user.referral.update', $client->id), [
            'referral_code' => 'VISM410',
            'referral_discount_percent' => 120,
        ])->assertStatus(422);

        // 6. Search by referral code in /user & /search-refer-users & CSV export
        $this->actingAs($admin);
        $searchHtml = $this->get('/user?user=VISM410')->assertStatus(200)->getContent();
        $this->assertStringContainsString('VISM410', $searchHtml);

        $referSearch = $this->getJson('/search-refer-users?search=VISM410');
        $referSearch->assertStatus(200)
            ->assertJsonFragment(['id' => $client->id, 'referral_code' => 'VISM410']);

        $exportResp = $this->get('/export-users?user=VISM410');
        $exportResp->assertStatus(200);
        $csvPath = $exportResp->getFile()->getPathname();
        $csvOutput = (string) file_get_contents($csvPath);
        @unlink($csvPath);
        $this->assertStringContainsString('ReferralCode,ReferralDiscountPercent', $csvOutput);
        $this->assertStringContainsString('VISM410', $csvOutput);
    }

    public function test_user3_exec2_discount10_format_with_name_collision_disambiguation(): void
    {
        $service = app(ReferralCodeService::class);
        $exec = $this->createUser([
            'name' => 'Usk Kelvin',
            'email' => strtolower($this->prefix) . 'exec_carol@example.test',
            'role_id' => 4,
            'referral_prefix' => 'US',
        ]);

        $carol = $this->createUser(['name' => 'Carol Chagas']);
        $rahul1 = $this->createUser(['name' => 'Rahul Sharma']);
        $rahul2 = $this->createUser(['name' => 'Rahul Verma']);

        // Exact specification: User prefix (3) + Marketing Exec prefix (2) + 10 => CARUS10
        $carolCode = $service->generateUniqueClientCode('US', $carol->name, 10.00, $carol->email, $carol->id);
        $this->assertSame('CARUS10', $carolCode);

        $rahul1Code = $service->generateUniqueClientCode('US', $rahul1->name, 10.00, $rahul1->email, $rahul1->id);
        $this->assertSame('RAHUS10', $rahul1Code);
        DB::table('users')->where('id', $rahul1->id)->update(['referral_code' => $rahul1Code]);

        // Second Rahul under same US exec collides on RAHUS10 -> disambiguates using client's own name words => RAVUS10
        $rahul2Code = $service->generateUniqueClientCode('US', $rahul2->name, 10.00, $rahul2->email, $rahul2->id);
        $this->assertNotSame($rahul1Code, $rahul2Code);
        $this->assertSame('RAVUS10', $rahul2Code);
    }
}
