<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class BreakTimeReportAccessTest extends TestCase
{
    protected string $prefix = 'ZZTEST_BREAK_';

    protected function setUp(): void
    {
        parent::setUp();
        $this->cleanupFixtures();
        DB::beginTransaction();
        Cache::forget('role-allowed-routes-9');
        Cache::forget('role-allowed-routes-4');
    }

    protected function tearDown(): void
    {
        while (DB::transactionLevel() > 0) {
            DB::rollBack();
        }
        $this->cleanupFixtures();
        Cache::forget('role-allowed-routes-9');
        Cache::forget('role-allowed-routes-4');
        parent::tearDown();
    }

    protected function cleanupFixtures(): void
    {
        $userIds = DB::table('users')->where('name', 'like', $this->prefix . '%')->pluck('id')->all();
        if (!empty($userIds)) {
            DB::table('user_break_times')->whereIn('user_id', $userIds)->delete();
            DB::table('users')->whereIn('id', $userIds)->delete();
        }
    }

    protected function makeUser(int $roleId, string $suffix): User
    {
        $email = strtolower($this->prefix . $suffix . '_' . uniqid()) . '@example.test';
        $id = DB::table('users')->insertGetId([
            'name' => $this->prefix . $suffix,
            'email' => $email,
            'password' => Hash::make('secret123'),
            'role_id' => $roleId,
            'flag' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return User::findOrFail($id);
    }

    public function test_any_subadmin_can_access_break_time_report_and_see_marketing_breaks(): void
    {
        $subAdmin = $this->makeUser(9, 'SubAdmin_Other');
        $marketing = $this->makeUser(4, 'Marketing_Emp');

        DB::table('user_break_times')->insert([
            'user_id' => $marketing->id,
            'break_type' => 'Lunch Break',
            'start_time' => now()->subMinutes(40),
            'end_time' => now()->subMinutes(10),
            'total_seconds' => 1800,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->actingAs($subAdmin)->get('/break-time-report');

        $response->assertStatus(200);
        $response->assertSee($marketing->name);
        $response->assertSee('Lunch Break');
    }

    public function test_sidebar_shows_break_time_report_to_all_subadmins_while_preserving_revoke_split(): void
    {
        $otherSubAdmin = $this->makeUser(9, 'SubAdmin_Sidebar');

        $otherResponse = $this->actingAs($otherSubAdmin)->get('/break-time-report');
        $otherResponse->assertStatus(200);
        $otherResponse->assertSee('href="' . url('break-time-report') . '"', false);
        $otherResponse->assertSee('Break Time Report');
        $otherResponse->assertSee('href="' . url('my-revoke-payments') . '"', false);
        $otherResponse->assertDontSee('href="' . url('my-break-time-report') . '"', false);

        $subAdmin13715 = User::find(13715);
        if ($subAdmin13715 && (int) $subAdmin13715->role_id === 9) {
            $resp13715 = $this->actingAs($subAdmin13715)->get('/break-time-report');
            $resp13715->assertStatus(200);
            $resp13715->assertSee('href="' . url('break-time-report') . '"', false);
            $resp13715->assertDontSee('href="' . url('my-break-time-report') . '"', false);
            $resp13715->assertDontSee('href="' . url('my-revoke-payments') . '"', false);
        }
    }

    public function test_super_admin_can_access_and_marketing_role_is_forbidden(): void
    {
        $superAdmin = $this->makeUser(1, 'SuperAdmin');
        $marketing = $this->makeUser(4, 'Marketing_Blocked');

        $this->actingAs($superAdmin)
            ->get('/break-time-report')
            ->assertStatus(200);

        $this->actingAs($marketing)
            ->get('/break-time-report')
            ->assertStatus(403);
    }
}
