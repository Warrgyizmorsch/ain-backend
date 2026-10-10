<?php

namespace App\Providers;

use App\Models\LoginOtpNotification;
use App\Models\menu;
use App\Models\Payment;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

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
        Schema::defaultStringLength(191);
        Paginator::useBootstrapFive();

        // mk
        // TEMP: slow query logging (test ke baad hata dena)
        \DB::listen(function ($q) {
            if ($q->time > 100) {
                \Log::info('SLOWQ '.round($q->time).'ms: '.substr($q->sql, 0, 200));
            }
        });
        // mmk

        if (! app()->runningInConsole()) {
            try {
                $menus = Cache::remember('global_portal_menus_tree', 1800, function () {
                    return menu::with(['children.submenus', 'submenus'])->get();
                });
                $premission = Cache::remember('global_portal_permissions', 1800, function () {
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
                $revokeCount = Cache::remember('global_revoke_count', 60, function () {
                    return Payment::where('is_revoked', 1)
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

                    $myRevokeCount = Cache::remember("my_revoke_count_{$userId}", 60, function () use ($roleId, $userName) {
                        $myRevokeQuery = Payment::where('is_revoked', 1)
                            ->where('revoke_resolved', 0)
                            ->whereHas('order', function ($q) {
                                $q->where('uid', '!=', 0);
                            });

                        if (! in_array($roleId, [1, 9])) {
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
                $loginOtpCount = Cache::remember('global_login_otp_count', 30, function () {
                    return LoginOtpNotification::where('status', 'pending')
                        ->where('purpose', 'user_admin_approval')
                        ->where(function ($query) {
                            $query->whereNull('expires_at')
                                ->orWhere('expires_at', '>', now());
                        })
                        ->count();
                });

                $loginOtpNotifications = Cache::remember('global_login_otp_notifications', 30, function () {
                    return LoginOtpNotification::with('user')
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
