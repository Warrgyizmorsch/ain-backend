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
