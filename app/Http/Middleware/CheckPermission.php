<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class CheckPermission
{
    /**
     * Module to Route and action mappings for granular permission matching.
     */
    protected array $moduleRouteMap = [
        'user' => [
            'user', 'usercreate', 'user-logs', 'user-history', 'user/report',
            'user/report-list', 'user-report', 'refer-user-report', 'user-retention-report',
            'user.update', 'user.delete', 'user.userlogs', 'user.feedbackhistory',
            'postChangePasswordAdmin', 'updateUser'
        ],
        'orders' => [
            'order', 'orders', 'edit', 'call', 'comment', 'orderpayments',
            'search', 'search-order', 'fetch-subwriters', 'search-writer',
            'search-writerTl', 'orderedit', 'orders/change-team', 'orders/save-referral',
            'update_status', 'update_date', 'status-details', 'order-writer', 'order-feedback',
            'orders.index', 'orders.filter', 'orders.comment.drawer', 'orders.payment.form',
            'orders.payment.form.store', 'orders.payment.form.update', 'urgent-orders',
            'prime-orders', 'smart-orders', 'prime-cancelled', 'smart-cancelled',
            'refund-orders', 'orders/looking-refund', 'orders/additional-save',
            'orders/additional-history', 'revoke-payments', 'my-revoke-payments'
        ],
        'lead' => [
            'lead', 'leads', 'checklead', 'convertleads', 'convert-lead',
            'cancel-lead', 'restore-lead', 'delete-lead', 'duplicate-lead',
            'duplicate-leads', 'leads-tracking-data', 'search-refer-users',
            'next-lead', 'followups', 'next-followups', 'prime-leads', 'smart-leads'
        ],
        'c-leads' => [
            'c-leads', 'cancel-leads'
        ],
        'master' => [
            'master', 'typeOfSecvices', 'typeofpaper', 'coupons', 'formatting',
            'Categories', 'categories', 'sources', 'college', 'Banks', 'banks',
            'status', 'Payments', 'payments', 'writer', 'writerTL', 'subwriter',
            'labels', 'failedJobs'
        ],
        'college' => [
            'college', 'colleges'
        ],
        'feedback' => [
            'feedback', 'feedback-list', 'feedback-update', 'feedback-delete'
        ],
        'follow-up' => [
            'follow-up', 'follow-up-report'
        ],
        'blog_list' => [
            'blog_list', 'write_blog', 'blog_edit', 'submit_blog', 'blogs', 'blog'
        ],
        'sample' => [
            'sample', 'samples', 'create_sample', 'submit_sample', 'free-sample',
            'free-sample-write', 'free-samples-type', 'sample-category', 'sample-type'
        ],
        'whatsapp' => [
            'whatsapp', 'webhooks/whatsapp'
        ],
        'emails' => [
            'emails', 'email'
        ],
        'menus' => [
            'menus', 'menu', 'submenu', 'userright', 'rolePermission'
        ],
        'plugins' => [
            'plugins', 'next2call', 'twilio', 'call-history'
        ],
        'next2call' => [
            'next2call', 'dialer-window', 'softphone'
        ],
        'group-master' => [
            'group-master'
        ],
        'review' => [
            'review', 'reviews', 'review-create', 'review-list', 'review-edit'
        ],
        'faq' => [
            'faq', 'faqs', 'faqurl', 'newfaq'
        ],
        'experts' => [
            'experts', 'expert', 'create-expert', 'new-expert'
        ],
    ];

    /**
     * Direct string/route aliases
     */
    protected array $aliases = [
        'orders' => ['order'],
        'order' => ['orders'],
        'lead' => ['leads'],
        'leads' => ['lead'],
        'c-leads' => ['cleads', 'cancel-leads'],
        'menus' => ['menu', 'submenu', 'userright'],
        'menu' => ['menus', 'submenu', 'userright'],
        'payments' => ['Payments', 'payment'],
        'Payments' => ['payments', 'payment'],
        'college' => ['colleges'],
        'blog_list' => ['write_blog', 'blog_edit', 'submit_blog', 'blogs', 'blog'],
        'user' => ['usercreate', 'user-logs'],
        'review' => ['reviews'],
        'faq' => ['faqs'],
        'experts' => ['expert'],
        'whatsapp' => ['whatsapp'],
        'emails' => ['emails', 'email'],
    ];

    public function handle($request, Closure $next)
    {
        $user = Auth::user();

        if (!$user) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'error' => 'Unauthenticated',
                    'message' => 'Please log in to continue.'
                ], 401);
            }
            return redirect()->route('login');
        }

        $roleId = (int) $user->role_id;

        // 1. Super Admin (Role 1) has unrestricted access to everything
        if ($roleId === 1) {
            return $next($request);
        }

        $currentRoute = Route::currentRouteName() ?? '';
        $currentPath = trim($request->path(), '/');

        // 2. Global Bypass for common authenticated user utilities & endpoints
        $bypassRoutes = [
            'dashboard',
            'profile',
            'profile.update',
            'postChangePassword',
            'csrf.token',
            'chatbot.index',
            'rolePermission',
            'emails.sync',
            'admin.login-otp-notifications',
            'admin.login-otp-notifications.pending',
            'user.break.start',
            'user.break.end',
            'user.break.current',
            'user.update-time',
            'dashboard.conversion.ratio.data',
            'logout',
        ];

        if ($currentRoute && in_array($currentRoute, $bypassRoutes, true)) {
            return $next($request);
        }

        $bypassPaths = [
            'dashboard',
            'profile',
            'change-password',
            'csrf-token',
            'chatbot',
            'logout',
            'user/break',
            'update-working-time',
            'admin/login-otp-notifications',
            'dashboard/conversion-ratio-data',
        ];

        foreach ($bypassPaths as $bp) {
            if ($currentPath === $bp || str_starts_with($currentPath, $bp . '/')) {
                return $next($request);
            }
        }

        // 3. Get Role Permissions (cached for 5 minutes per role)
        $allowedRoutes = Cache::remember('role-allowed-routes-' . $roleId, now()->addMinutes(5), function () use ($roleId) {
            $permission = DB::table('permission')->where('role_id', $roleId)->first();
            if (!$permission) {
                return [];
            }

            $menuIdsRaw = json_decode($permission->menu_id, true) ?? [];
            $submenuIdsRaw = json_decode($permission->submenu_id, true) ?? [];

            $menuIds = is_array($menuIdsRaw) ? $menuIdsRaw : (is_numeric($menuIdsRaw) ? [$menuIdsRaw] : []);
            $submenuIds = is_array($submenuIdsRaw) ? $submenuIdsRaw : (is_numeric($submenuIdsRaw) ? [$submenuIdsRaw] : []);

            $menuRoutes = DB::table('menu')
                ->whereIn('id', $menuIds)
                ->whereNotNull('routes')
                ->pluck('routes')
                ->filter()
                ->toArray();

            $submenuRoutes = DB::table('submenus')
                ->whereIn('id', $submenuIds)
                ->whereNotNull('routes')
                ->pluck('routes')
                ->filter()
                ->toArray();

            return array_values(array_unique(array_merge($menuRoutes, $submenuRoutes)));
        });

        if (empty($allowedRoutes)) {
            return $this->denyAccess($request);
        }

        // 4. Build expanded list of candidates from allowed routes, aliases, and module maps
        $allAllowedCandidates = [];
        foreach ($allowedRoutes as $ar) {
            $clean = trim((string) $ar, '/');
            if ($clean === '') continue;

            $allAllowedCandidates[] = $clean;

            // Direct aliases
            if (isset($this->aliases[$clean])) {
                foreach ($this->aliases[$clean] as $alias) {
                    $allAllowedCandidates[] = $alias;
                }
            }

            // Mapped action routes
            if (isset($this->moduleRouteMap[$clean])) {
                foreach ($this->moduleRouteMap[$clean] as $subRoute) {
                    $allAllowedCandidates[] = $subRoute;
                }
            }
        }
        $allAllowedCandidates = array_unique($allAllowedCandidates);

        // 5. Check if current path or route name matches any allowed candidate
        foreach ($allAllowedCandidates as $cand) {
            $candClean = trim($cand, '/');
            if ($candClean === '') continue;

            $pathPattern = '#^' . preg_quote($candClean, '#') . '(/|\?|$)#i';
            $routeClean = str_replace('/', '.', $candClean);
            $routePattern = '#^' . preg_quote($routeClean, '#') . '(\.|$|/)#i';

            if (
                preg_match($pathPattern, $currentPath) ||
                ($currentRoute && (preg_match($routePattern, $currentRoute) || preg_match($pathPattern, $currentRoute)))
            ) {
                return $next($request);
            }
        }

        // 6. Deny access if route is not authorized
        return $this->denyAccess($request);
    }

    /**
     * Return friendly 403 response
     */
    protected function denyAccess($request)
    {
        $message = 'You do not have user rights to access this page.';

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => false,
                'error' => 'Forbidden',
                'message' => $message,
            ], 403);
        }

        return response()->view('errors.403', [
            'message' => $message,
        ], 403);
    }
}
