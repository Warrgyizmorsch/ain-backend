<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\UserDeviceToken;
use App\Services\FirebaseNotificationService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class PushNotificationTest extends TestCase
{
    use DatabaseTransactions;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::firstOrCreate(
            ['email' => 'test_fcm_user@example.com'],
            [
                'name' => 'FCM Test User',
                'password' => bcrypt('password123'),
                'role_id' => 2,
            ]
        );
    }

    public function test_save_device_token_successfully(): void
    {
        Sanctum::actingAs($this->user);

        $response = $this->postJson('/api/app/save-device-token', [
            'fcm_token' => 'sample_fcm_token_12345',
            'device_type' => 'android',
            'device_name' => 'Pixel 8 Pro',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Device token registered successfully.',
            ]);

        $this->assertDatabaseHas('user_device_tokens', [
            'user_id' => $this->user->id,
            'fcm_token' => 'sample_fcm_token_12345',
            'device_type' => 'android',
            'device_name' => 'Pixel 8 Pro',
            'is_active' => 1,
        ]);
    }

    public function test_save_device_token_upserts_existing_token(): void
    {
        Sanctum::actingAs($this->user);

        // First save
        $this->postJson('/api/app/save-device-token', [
            'fcm_token' => 'sample_fcm_token_upsert',
            'device_type' => 'android',
            'device_name' => 'Old Phone',
        ]);

        // Re-register same token with new info
        $response = $this->postJson('/api/app/save-device-token', [
            'fcm_token' => 'sample_fcm_token_upsert',
            'device_type' => 'ios',
            'device_name' => 'iPhone 15 Pro',
        ]);

        $response->assertStatus(200);

        // Should only have 1 row in database
        $this->assertEquals(
            1,
            UserDeviceToken::where('fcm_token', 'sample_fcm_token_upsert')->count()
        );

        $this->assertDatabaseHas('user_device_tokens', [
            'user_id' => $this->user->id,
            'fcm_token' => 'sample_fcm_token_upsert',
            'device_type' => 'ios',
            'device_name' => 'iPhone 15 Pro',
        ]);
    }

    public function test_save_device_token_validation_error(): void
    {
        Sanctum::actingAs($this->user);

        $response = $this->postJson('/api/app/save-device-token', [
            // Missing fcm_token
            'device_type' => 'android',
        ]);

        $response->assertStatus(422)
            ->assertJson([
                'success' => false,
                'message' => 'Validation error',
            ]);
    }

    public function test_remove_device_token_on_logout(): void
    {
        Sanctum::actingAs($this->user);

        UserDeviceToken::create([
            'user_id' => $this->user->id,
            'fcm_token' => 'token_to_deactivate_logout',
            'device_type' => 'android',
            'is_active' => true,
        ]);

        $response = $this->postJson('/api/app/remove-device-token', [
            'fcm_token' => 'token_to_deactivate_logout',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Device token removed successfully.',
            ]);

        $this->assertDatabaseHas('user_device_tokens', [
            'user_id' => $this->user->id,
            'fcm_token' => 'token_to_deactivate_logout',
            'is_active' => 0,
        ]);
    }

    public function test_unauthenticated_request_rejected(): void
    {
        $response = $this->postJson('/api/app/save-device-token', [
            'fcm_token' => 'some_token',
        ]);

        $response->assertStatus(401);
    }

    public function test_user_device_tokens_relationship(): void
    {
        UserDeviceToken::create([
            'user_id' => $this->user->id,
            'fcm_token' => 'rel_test_token_99',
            'device_type' => 'android',
            'is_active' => true,
        ]);

        $this->assertTrue($this->user->deviceTokens()->where('fcm_token', 'rel_test_token_99')->exists());
    }

    public function test_firebase_notification_service_handles_no_tokens_gracefully(): void
    {
        $fcmService = app(FirebaseNotificationService::class);

        $fakeUserId = 9999999;
        $result = $fcmService->sendToUser($fakeUserId, 'Title', 'Body');

        $this->assertFalse($result['success']);
        $this->assertEquals(0, $result['sent']);
        $this->assertEquals('No active device tokens found for this user.', $result['message']);
    }
}
