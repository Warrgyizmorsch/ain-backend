<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\UserDeviceToken;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class NotificationApiController extends Controller
{
    /**
     * Register or update an FCM device token for the authenticated mobile user.
     * POST /api/app/save-device-token
     */
    public function saveDeviceToken(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'fcm_token' => 'required|string|max:500',
            'device_type' => 'nullable|string|in:android,ios,web',
            'device_name' => 'nullable|string|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $user = $request->user();
        $fcmToken = trim($request->input('fcm_token'));
        $deviceType = strtolower($request->input('device_type', 'android'));
        $deviceName = $request->input('device_name');

        $deviceToken = UserDeviceToken::updateOrCreate(
            ['fcm_token' => $fcmToken],
            [
                'user_id' => $user->id,
                'device_type' => $deviceType,
                'device_name' => $deviceName,
                'is_active' => true,
                'last_used_at' => now(),
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Device token registered successfully.',
            'data' => [
                'id' => $deviceToken->id,
                'user_id' => $deviceToken->user_id,
                'device_type' => $deviceToken->device_type,
                'is_active' => (bool)$deviceToken->is_active,
                'updated_at' => $deviceToken->updated_at,
            ],
        ]);
    }

    /**
     * Deactivate an FCM device token when user logs out.
     * POST /api/app/remove-device-token
     */
    public function removeDeviceToken(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'fcm_token' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $user = $request->user();
        $fcmToken = trim($request->input('fcm_token'));

        $affected = UserDeviceToken::where('user_id', $user->id)
            ->where('fcm_token', $fcmToken)
            ->update(['is_active' => false]);

        return response()->json([
            'success' => true,
            'message' => $affected > 0 ? 'Device token removed successfully.' : 'Token not found or already inactive.',
        ]);
    }
}
