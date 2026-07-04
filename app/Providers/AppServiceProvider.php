<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\menu;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\Paginator;



class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $menus = menu::all();
        $premission = DB::table('permission')->get();
        view()->share('menus', $menus);
        view()->share('premission', $premission);
        Paginator::useBootstrapFive();

        view()->composer('layouts.aside', function ($view) {
            $revokeCount = 0;
            $myRevokeCount = 0;

            try {
                $revokeCount = \App\Models\Payment::where('is_revoked', 1)
                    ->where('revoke_resolved', 0)
                    ->whereHas('order', function ($q) {
                        $q->where('uid', '!=', 0);
                    })
                    ->count();

                $myRevokeQuery = \App\Models\Payment::where('is_revoked', 1)
                    ->where('revoke_resolved', 0)
                    ->whereHas('order', function ($q) {
                        $q->where('uid', '!=', 0);
                    });

                if (auth()->check()) {
                    $roleId = auth()->user()->role_id;
                    if (!in_array($roleId, [1, 9])) {
                        if ($roleId == 4) {
                            $myRevokeQuery->where('payment_update_by', auth()->user()->name);
                        } else {
                            $myRevokeQuery->whereRaw('1 = 0');
                        }
                    }
                }
                $myRevokeCount = $myRevokeQuery->count();
            } catch (\Exception $e) {
                // Prevent issues during migrations or database seeders
            }

            $view->with([
                'globalRevokeCount' => $revokeCount,
                'globalMyRevokeCount' => $myRevokeCount
            ]);
        });

        view()->composer(['layouts.header', 'layouts.aside'], function ($view) {
            $loginOtpCount = 0;
            $loginOtpNotifications = collect();

            try {
                $loginOtpCount = \App\Models\LoginOtpNotification::where('status', 'pending')
                    ->where('purpose', 'user_admin_approval')
                    ->where(function ($query) {
                        $query->whereNull('expires_at')
                            ->orWhere('expires_at', '>', now());
                    })
                    ->count();

                $loginOtpNotifications = \App\Models\LoginOtpNotification::with('user')
                    ->where('purpose', 'user_admin_approval')
                    ->latest()
                    ->limit(5)
                    ->get();
            } catch (\Exception $e) {
                // Prevent issues before the OTP notification table is migrated.
            }

            $view->with([
                'globalLoginOtpCount' => $loginOtpCount,
                'globalLoginOtpNotifications' => $loginOtpNotifications,
            ]);
        });
    }
}
