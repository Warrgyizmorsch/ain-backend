<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Log;
use App\Models\Permission;
use App\Models\Menu;
use App\Models\Submenus;
use Illuminate\Support\Str;

class CheckPermission
{
    public function handle($request, Closure $next)
    {
        $user = Auth::user();

        if (!$user) {
            return abort(403, 'Unauthenticated access');
        }

        $roleId = $user->role_id;
        $currentRoute = Route::currentRouteName();
        $currentPath = $request->path();

        // ✅ Skip permission check for some routes
        $bypassRoutes = ['rolePermission'];
        if (in_array($currentRoute, $bypassRoutes)) {
            return $next($request);
        }

        // ✅ Get role permissions
        $permission = Permission::where('role_id', $roleId)->first();
        if (!$permission) {
            return abort(403, 'Permission denied');
        }

        $menuIdsRaw = json_decode($permission->menu_id, true);
        $submenuIdsRaw = json_decode($permission->submenu_id, true);

        $menuIds = is_array($menuIdsRaw) ? $menuIdsRaw : (is_numeric($menuIdsRaw) ? [$menuIdsRaw] : []);
        $submenuIds = is_array($submenuIdsRaw) ? $submenuIdsRaw : (is_numeric($submenuIdsRaw) ? [$submenuIdsRaw] : []);


        $allowedMenuRoutes = Menu::whereIn('id', $menuIds)->pluck('routes')->filter()->toArray();
        $allowedSubmenuRoutes = Submenus::whereIn('id', $submenuIds)->pluck('routes')->filter()->toArray();

        $allowedRoutes = array_merge($allowedMenuRoutes, $allowedSubmenuRoutes);

        // ✅ Allow if current route or path starts with any allowed route
        foreach ($allowedRoutes as $allowedRoute) {
            $pattern = '#^' . preg_quote($allowedRoute, '#') . '(/|$)#';

            if (
                ($currentRoute && preg_match($pattern, $currentRoute)) ||
                preg_match($pattern, $currentPath)
            ) {
                return $next($request);
            }
        }

        return abort(404);
    }
}
