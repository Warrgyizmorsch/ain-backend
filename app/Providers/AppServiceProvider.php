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
        Paginator::useBootstrapFive();

        if (!app()->runningInConsole()) {
            try {
                $menus = \Illuminate\Support\Facades\Cache::remember('global_portal_menus_tree', 1800, function () {
                    return menu::with(['children.submenus', 'submenus'])->get();
                });
                $premission = \Illuminate\Support\Facades\Cache::remember('global_portal_permissions', 1800, function () {
                    return DB::table('permission')->get();
                });
                view()->share('menus', $menus);
                view()->share('premission', $premission);
            } catch (\Throwable $e) {
                // Prevent issues during early boot or install
            }
        }

        view()->composer('layouts.aside', function ($view) {
            $revokeCount = 0;
            $myRevokeCount = 0;

            try {
                $revokeCount = \Illuminate\Support\Facades\Cache::remember('global_revoke_count', 60, function () {
                    return \App\Models\Payment::where('is_revoked', 1)
                        ->where('revoke_resolved', 0)
                        ->whereHas('order', function ($q) {
                            $q->where('uid', '!=', 0);
                        })
                        ->count();
                });

                if (auth()->check()) {
                    $userId = auth()->id();
                    $roleId = auth()->user()->role_id;
                    $userName = auth()->user()->name;

                    $myRevokeCount = \Illuminate\Support\Facades\Cache::remember("my_revoke_count_{$userId}", 60, function () use ($roleId, $userName) {
                        $myRevokeQuery = \App\Models\Payment::where('is_revoked', 1)
                            ->where('revoke_resolved', 0)
                            ->whereHas('order', function ($q) {
                                $q->where('uid', '!=', 0);
                            });

                        if (!in_array($roleId, [1, 9])) {
                            if ($roleId == 4) {
                                $myRevokeQuery->where('payment_update_by', $userName);
                            } else {
                                $myRevokeQuery->whereRaw('1 = 0');
                            }
                        }

                        return $myRevokeQuery->count();
                    });
                }
            } catch (\Exception $e) {
                // Prevent issues during migrations or database seeders
            }

            $view->with([
                'globalRevokeCount' => $revokeCount,
                'globalMyRevokeCount' => $myRevokeCount,
            ]);
        });

        view()->composer(['layouts.header', 'layouts.aside'], function ($view) {
            $loginOtpCount = 0;
            $loginOtpNotifications = collect();

            try {
                $loginOtpCount = \Illuminate\Support\Facades\Cache::remember('global_login_otp_count', 30, function () {
                    return \App\Models\LoginOtpNotification::where('status', 'pending')
                        ->where('purpose', 'user_admin_approval')
                        ->where(function ($query) {
                            $query->whereNull('expires_at')
                                ->orWhere('expires_at', '>', now());
                        })
                        ->count();
                });

                $loginOtpNotifications = \Illuminate\Support\Facades\Cache::remember('global_login_otp_notifications', 30, function () {
                    return \App\Models\LoginOtpNotification::with('user')
                        ->where('purpose', 'user_admin_approval')
                        ->latest()
                        ->limit(5)
                        ->get();
                });
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
