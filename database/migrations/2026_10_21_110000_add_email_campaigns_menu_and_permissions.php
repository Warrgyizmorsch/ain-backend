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
        if (Schema::hasTable('menu')) {
            $existing = DB::table('menu')
                ->where('routes', 'emails/campaigns')
                ->orWhere('menu_name', 'Email Campaigns')
                ->first();

            if (!$existing) {
                $menuId = DB::table('menu')->insertGetId([
                    'menu_name' => 'Email Campaigns',
                    'icon_class' => 'fa fa-bullhorn',
                    'show_menu' => 'Y',
                    'routes' => 'emails/campaigns',
                    'sort_order' => 4,
                    'parent_id' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } else {
                $menuId = $existing->id;
                DB::table('menu')->where('id', $menuId)->update([
                    'menu_name' => 'Email Campaigns',
                    'icon_class' => 'fa fa-bullhorn',
                    'show_menu' => 'Y',
                    'routes' => 'emails/campaigns',
                    'sort_order' => 4,
                    'parent_id' => null,
                    'updated_at' => now(),
                ]);
            }

            // Grant permissions to:
            // Role 1: Super Admin (Admin)
            // Role 4: Marketing Team
            // Role 9: Sub Admin
            if (Schema::hasTable('permission')) {
                $targetRoleIds = [1, 4, 9];

                foreach ($targetRoleIds as $roleId) {
                    $rolePerm = DB::table('permission')->where('role_id', $roleId)->first();
                    $menuIdStr = (string) $menuId;

                    if ($rolePerm) {
                        $menuIds = json_decode($rolePerm->menu_id, true) ?? [];
                        $menuIdsStr = array_map('strval', is_array($menuIds) ? $menuIds : [$menuIds]);

                        if (!in_array($menuIdStr, $menuIdsStr, true)) {
                            $menuIdsStr[] = $menuIdStr;
                            DB::table('permission')->where('role_id', $roleId)->update([
                                'menu_id' => json_encode(array_values(array_unique($menuIdsStr))),
                                'updated_at' => now(),
                            ]);
                        }
                    } else {
                        DB::table('permission')->insert([
                            'role_id' => $roleId,
                            'menu_id' => json_encode([$menuIdStr]),
                            'submenu_id' => json_encode([]),
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }

                    Cache::forget('role-allowed-routes-' . $roleId);
                    Cache::forget('role-allowed-routes-' . (int) $roleId);
                }
            }

            // Invalidate global caches so navigation and user rights UI refresh instantly
            Cache::forget('global_portal_menus_tree');
            Cache::forget('global_portal_permissions');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('menu')) {
            $menu = DB::table('menu')->where('routes', 'emails/campaigns')->first();
            if ($menu) {
                $menuIdStr = (string) $menu->id;

                if (Schema::hasTable('permission')) {
                    foreach ([1, 4, 9] as $roleId) {
                        $rolePerm = DB::table('permission')->where('role_id', $roleId)->first();
                        if ($rolePerm) {
                            $menuIds = json_decode($rolePerm->menu_id, true) ?? [];
                            $menuIdsStr = array_values(array_filter(
                                array_map('strval', is_array($menuIds) ? $menuIds : [$menuIds]),
                                fn($id) => $id !== $menuIdStr
                            ));
                            DB::table('permission')->where('role_id', $roleId)->update([
                                'menu_id' => json_encode($menuIdsStr),
                                'updated_at' => now(),
                            ]);
                        }
                    }
                }

                DB::table('menu')->where('id', $menu->id)->delete();
            }

            Cache::forget('global_portal_menus_tree');
            Cache::forget('global_portal_permissions');
        }
    }
};
