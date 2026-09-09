<?php

use App\Models\UserLog;
use Illuminate\Support\Facades\Auth;

if (!function_exists('getUserRoleName')) {
    function getUserRoleName($role_id)
    {
        return match ($role_id) {
            1 => 'Admin',
            2 => 'User',
            3 => 'Feedback Team',
            4 => 'Marketing Team',
            5 => 'Project Team',
            6 => 'Writer Team Leader',
            7 => 'Writer Team',
            8 => 'Writer Admin',
            9 => 'Sub Admin',
            default => 'Unknown',
        };
    }
}

if (!function_exists('mask_phone_for_display')) {
    /**
     * Show full number only to admins (role_id 1). Everyone else sees
     * the first 4 and last 4 digits, with the middle masked as ****.
     */
    function mask_phone_for_display(?string $countryCode, ?string $mobile): string
    {
        $digits = preg_replace('/\D+/', '', (string) $countryCode) . preg_replace('/\D+/', '', (string) $mobile);

        if ($digits === '') {
            return 'N/A';
        }

        if (Auth::check() && (int) Auth::user()->role_id === 1) {
            return $digits;
        }

        if (strlen($digits) <= 6) {
            return str_repeat('*', strlen($digits));
        }

        return substr($digits, 0, 2) . str_repeat('*', strlen($digits) - 6) . substr($digits, -4);
    }
}

if (!function_exists('logActivity')) {
    function logActivity($module, $action)
    {
        
        if (!Auth::check()) {
            return; 
        }
        try {
            UserLog::create([
                'user_id' => Auth::id(),
                'module' => $module,
                'action' => $action,
            ]);
            return ['status' => true, 'msg' => 'Log saved'];
        } catch (\Exception $e) {
            return ['status' => false, 'msg' => $e->getMessage()];
            // ignore error
        }
    }
}
