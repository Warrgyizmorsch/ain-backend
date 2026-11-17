<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserDeviceToken;
use Illuminate\Support\Facades\Log;
use Kreait\Firebase\Contract\Messaging;
use Kreait\Firebase\Exception\Messaging\NotFound;
use Kreait\Firebase\Exception\Messaging\InvalidMessage;
use Kreait\Firebase\Messaging\AndroidConfig;
use Kreait\Firebase\Messaging\ApnsConfig;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;

class FirebaseNotificationService
{
    protected ?Messaging $messaging = null;

    public function __construct()
    {
        try {
            $this->messaging = app(Messaging::class);
        } catch (\Throwable $e) {
            Log::error('FirebaseNotificationService initialization failed: ' . $e->getMessage());
        }
    }

    /**
     * Send push notification to a specific FCM token.
     */
    public function sendToToken(string $fcmToken, string $title, string $body, array $data = []): array
    {
        if (!$this->messaging) {
            return [
                'success' => false,
                'error' => 'Firebase Messaging is not configured or unavailable.',
            ];
        }

        try {
            // Stringify all data values for FCM compliance
            $stringData = [];
            foreach ($data as $k => $v) {
                $stringData[(string)$k] = is_scalar($v) ? (string)$v : json_encode($v);
            }

            $message = CloudMessage::withTarget('token', $fcmToken)
                ->withNotification(Notification::create($title, $body))
                ->withData($stringData)
                ->withAndroidConfig(AndroidConfig::fromArray([
                    'priority' => 'high',
                    'notification' => [
                        'channel_id' => 'ain_notifications',
                        'sound' => 'default',
                        'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
                    ],
                ]))
                ->withApnsConfig(ApnsConfig::fromArray([
                    'headers' => [
                        'apns-priority' => '10',
                    ],
                    'payload' => [
                        'aps' => [
                            'sound' => 'default',
                            'badge' => 1,
                        ],
                    ],
                ]));

            $result = $this->messaging->send($message);

            // Update last used timestamp
            UserDeviceToken::where('fcm_token', $fcmToken)->update([
                'last_used_at' => now(),
                'is_active' => true,
            ]);

            return [
                'success' => true,
                'message_id' => is_string($result) ? $result : json_encode($result),
            ];
        } catch (NotFound $e) {
            // Token is invalid, expired or unregistered on Google servers
            Log::info("FCM token expired or not found. Deactivating: {$fcmToken}");
            UserDeviceToken::where('fcm_token', $fcmToken)->update(['is_active' => false]);

            return [
                'success' => false,
                'error' => 'Token unregistered or not found on Firebase.',
            ];
        } catch (InvalidMessage $e) {
            Log::warning("Invalid FCM message payload: " . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        } catch (\Throwable $e) {
            Log::error("FCM send error: " . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Send push notification to a user's registered active devices.
     */
    public function sendToUser($user, string $title, string $body, array $data = []): array
    {
        $userId = $user instanceof User ? $user->id : (int)$user;

        $tokens = UserDeviceToken::where('user_id', $userId)
            ->where('is_active', true)
            ->pluck('fcm_token')
            ->all();

        if (empty($tokens)) {
            return [
                'success' => false,
                'sent' => 0,
                'failed' => 0,
                'message' => 'No active device tokens found for this user.',
            ];
        }

        $sentCount = 0;
        $failedCount = 0;

        foreach ($tokens as $token) {
            $res = $this->sendToToken($token, $title, $body, $data);
            if (!empty($res['success'])) {
                $sentCount++;
            } else {
                $failedCount++;
            }
        }

        return [
            'success' => $sentCount > 0,
            'sent' => $sentCount,
            'failed' => $failedCount,
        ];
    }

    /**
     * Send push notification to multiple users.
     */
    public function sendToMultipleUsers(array $userIds, string $title, string $body, array $data = []): array
    {
        $tokens = UserDeviceToken::whereIn('user_id', $userIds)
            ->where('is_active', true)
            ->pluck('fcm_token')
            ->all();

        if (empty($tokens)) {
            return [
                'success' => false,
                'sent' => 0,
                'failed' => 0,
                'message' => 'No active device tokens found for the requested users.',
            ];
        }

        $sentCount = 0;
        $failedCount = 0;

        foreach ($tokens as $token) {
            $res = $this->sendToToken($token, $title, $body, $data);
            if (!empty($res['success'])) {
                $sentCount++;
            } else {
                $failedCount++;
            }
        }

        return [
            'success' => $sentCount > 0,
            'sent' => $sentCount,
            'failed' => $failedCount,
        ];
    }

    /**
     * Send push notification to a Firebase Topic (e.g. "all_users", "promotions").
     */
    public function sendToTopic(string $topic, string $title, string $body, array $data = []): array
    {
        if (!$this->messaging) {
            return [
                'success' => false,
                'error' => 'Firebase Messaging is not configured or unavailable.',
            ];
        }

        try {
            $stringData = [];
            foreach ($data as $k => $v) {
                $stringData[(string)$k] = is_scalar($v) ? (string)$v : json_encode($v);
            }

            $message = CloudMessage::withTarget('topic', $topic)
                ->withNotification(Notification::create($title, $body))
                ->withData($stringData)
                ->withAndroidConfig(AndroidConfig::fromArray([
                    'priority' => 'high',
                    'notification' => [
                        'channel_id' => 'ain_notifications',
                        'sound' => 'default',
                    ],
                ]));

            $result = $this->messaging->send($message);

            return [
                'success' => true,
                'message_id' => is_string($result) ? $result : json_encode($result),
            ];
        } catch (\Throwable $e) {
            Log::error("FCM topic send error: " . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }
}
