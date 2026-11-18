<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Cache;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('submenus')) {
            // Find parent Reports menu
            $reportsMenu = DB::table('menu')
                ->where('id', 40)
                ->orWhere('routes', 'reports')
                ->orWhere('menu_name', 'Reports')
                ->first();

            $reportsMenuId = $reportsMenu ? $reportsMenu->id : 40;

            // Check if Customer Due Report submenu already exists
            $existingSubmenu = DB::table('submenus')
                ->where('routes', 'customer-due-report')
                ->first();

            if (!$existingSubmenu) {
                $submenuId = DB::table('submenus')->insertGetId([
                    'sub_menu_name' => 'Customer Due Report',
                    'menus_id'      => $reportsMenuId,
                    'routes'        => 'customer-due-report',
                    'sort_order'    => 13,
                    'show'          => 'Y',
                    'created_at'    => now(),
                    'updated_at'    => now(),
                ]);
            } else {
                $submenuId = $existingSubmenu->id;
                DB::table('submenus')
                    ->where('id', $submenuId)
                    ->update([
                        'sub_menu_name' => 'Customer Due Report',
                        'menus_id'      => $reportsMenuId,
                        'routes'        => 'customer-due-report',
                        'sort_order'    => 13,
                        'show'          => 'Y',
                        'updated_at'    => now(),
                    ]);
            }

            // Grant permission to initial roles:
            // Role 1: Super Admin
            // Role 3: QC Executive / Admin
            // Role 4: Marketing Team
            // Role 9: Sub Admin
            if (Schema::hasTable('permission')) {
                $initialRoleIds = [1, 3, 4, 9];
                $submenuIdStr = (string) $submenuId;
                $reportsMenuIdStr = (string) $reportsMenuId;

                foreach ($initialRoleIds as $roleId) {
                    $rolePerm = DB::table('permission')->where('role_id', $roleId)->first();

                    if ($rolePerm) {
                        $menuIds = json_decode($rolePerm->menu_id, true) ?? [];
                        $menuIdsStr = array_map('strval', is_array($menuIds) ? $menuIds : [$menuIds]);

                        $subIds = json_decode($rolePerm->submenu_id, true) ?? [];
                        $subIdsStr = array_map('strval', is_array($subIds) ? $subIds : [$subIds]);

                        $changed = false;

                        // Ensure parent Reports menu is present in role's menu_id
                        if (!in_array($reportsMenuIdStr, $menuIdsStr, true)) {
                            $menuIdsStr[] = $reportsMenuIdStr;
                            $changed = true;
                        }

                        // Ensure Customer Due Report submenu is present in role's submenu_id
                        if (!in_array($submenuIdStr, $subIdsStr, true)) {
                            $subIdsStr[] = $submenuIdStr;
                            $changed = true;
                        }

                        if ($changed) {
                            DB::table('permission')
                                ->where('role_id', $roleId)
                                ->update([
                                    'menu_id'    => json_encode(array_values(array_unique($menuIdsStr))),
                                    'submenu_id' => json_encode(array_values(array_unique($subIdsStr))),
                                    'updated_at' => now(),
                                ]);
                        }
                    } else {
                        // Create permission row if not exists
                        DB::table('permission')->insert([
                            'role_id'    => $roleId,
                            'menu_id'    => json_encode([$reportsMenuIdStr]),
                            'submenu_id' => json_encode([$submenuIdStr]),
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }

                    Cache::forget('role-allowed-routes-' . $roleId);
                    Cache::forget('role-allowed-routes-' . (int) $roleId);
                }
            }

            // Invalidate global portal caches
            Cache::forget('global_portal_menus_tree');
            Cache::forget('global_portal_permissions');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('submenus')) {
            $submenu = DB::table('submenus')
                ->where('routes', 'customer-due-report')
                ->first();

            if ($submenu && Schema::hasTable('permission')) {
                $subIdStr = (string) $submenu->id;
                $allPerms = DB::table('permission')->get();

                foreach ($allPerms as $perm) {
                    $subIds = json_decode($perm->submenu_id, true) ?? [];
                    $subIdsStr = array_map('strval', is_array($subIds) ? $subIds : [$subIds]);

                    if (in_array($subIdStr, $subIdsStr, true)) {
                        $updated = array_values(array_diff($subIdsStr, [$subIdStr]));
                        DB::table('permission')
                            ->where('id', $perm->id)
                            ->update([
                                'submenu_id' => json_encode($updated),
                                'updated_at' => now(),
                            ]);
                    }
                    Cache::forget('role-allowed-routes-' . $perm->role_id);
                }

                DB::table('submenus')->where('id', $submenu->id)->delete();
            }

            Cache::forget('global_portal_menus_tree');
            Cache::forget('global_portal_permissions');
        }
    }
};
