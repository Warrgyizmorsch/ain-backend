<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\UserDeviceToken;
use App\Services\FirebaseNotificationService;
use Illuminate\Console\Command;

class TestFirebaseNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fcm:test 
                            {target : User ID, Device Token, or Topic Name} 
                            {--title=Test Notification : Notification Title} 
                            {--body=This is a test notification from AIN Backend : Notification Body}
                            {--topic : Treat target as a topic name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send a test FCM push notification via Firebase';

    /**
     * Execute the console command.
     */
    public function handle(FirebaseNotificationService $fcmService): int
    {
        $target = $this->argument('target');
        $title = $this->option('title');
        $body = $this->option('body');
        $isTopic = $this->option('topic');

        $this->info("=== AIN Firebase Push Notification Test ===");
        $this->line("Target: {$target}");
        $this->line("Title:  {$title}");
        $this->line("Body:   {$body}");

        if ($isTopic) {
            $this->info("Sending to topic: {$target}...");
            $res = $fcmService->sendToTopic($target, $title, $body, ['test' => 'true', 'timestamp' => (string)time()]);
            if (!empty($res['success'])) {
                $this->info("SUCCESS: Topic notification sent! Message ID: " . ($res['message_id'] ?? 'OK'));
                return self::SUCCESS;
            }
            $this->error("FAILED: " . ($res['error'] ?? 'Unknown error'));
            return self::FAILURE;
        }

        if (is_numeric($target)) {
            $userId = (int)$target;
            $user = User::find($userId);
            if (!$user) {
                $this->error("User with ID {$userId} not found in database.");
                return self::FAILURE;
            }

            $tokensCount = UserDeviceToken::where('user_id', $userId)->where('is_active', true)->count();
            $this->line("Found {$tokensCount} active device token(s) for User: {$user->name} ({$user->email})");

            if ($tokensCount === 0) {
                $this->warn("No active device tokens found for user {$userId}. User must log in via the AIN App first.");
                return self::FAILURE;
            }

            $res = $fcmService->sendToUser($userId, $title, $body, ['type' => 'test', 'user_id' => (string)$userId]);
            if (!empty($res['success'])) {
                $this->info("SUCCESS: Sent to {$res['sent']} device(s). Failed: {$res['failed']}");
                return self::SUCCESS;
            }
            $this->error("FAILED: No messages could be delivered. Failed count: {$res['failed']}");
            return self::FAILURE;
        }

        // Target is a direct FCM token
        $this->info("Sending directly to FCM registration token...");
        $res = $fcmService->sendToToken($target, $title, $body, ['type' => 'direct_test']);
        if (!empty($res['success'])) {
            $this->info("SUCCESS: Notification delivered! Message ID: " . ($res['message_id'] ?? 'OK'));
            return self::SUCCESS;
        }

        $this->error("FAILED: " . ($res['error'] ?? 'Unknown error'));
        return self::FAILURE;
    }
}
