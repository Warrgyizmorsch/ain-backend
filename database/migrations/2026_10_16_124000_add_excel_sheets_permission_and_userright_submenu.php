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
        // 1. Grant Excel Sheets permission to Sub Admin (role 9) and Marketing Team (role 4)
        if (Schema::hasTable('menu') && Schema::hasTable('permission')) {
            $excelMenu = DB::table('menu')
                ->where('routes', 'spreadsheets')
                ->orWhere('menu_name', 'Excel Sheets')
                ->first();

            if ($excelMenu) {
                $targetRoleIds = [1, 4, 9]; // Super Admin (1), Marketing Team (4), Sub Admin (9)

                foreach ($targetRoleIds as $rId) {
                    $rolePerm = DB::table('permission')->where('role_id', $rId)->first();
                    if ($rolePerm) {
                        $menuIds = json_decode($rolePerm->menu_id, true) ?? [];
                        $menuIdsStr = array_map('strval', is_array($menuIds) ? $menuIds : [$menuIds]);
                        $excelMenuIdStr = (string) $excelMenu->id;

                        if (!in_array($excelMenuIdStr, $menuIdsStr, true)) {
                            $menuIdsStr[] = $excelMenuIdStr;
                            DB::table('permission')->where('role_id', $rId)->update([
                                'menu_id' => json_encode(array_values(array_unique($menuIdsStr))),
                                'updated_at' => now(),
                            ]);
                        }
                    }
                    Cache::forget('role-allowed-routes-' . $rId);
                }
            }
        }

        // 2. Ensure User Right is removed from User Module (menus_id = 4)
        if (Schema::hasTable('submenus')) {
            DB::table('submenus')
                ->where('menus_id', 4)
                ->where(function ($q) {
                    $q->where('routes', 'userright')->orWhere('sub_menu_name', 'User Right');
                })
                ->delete();
        }

        // 3. Invalidate caches immediately
        Cache::forget('global_portal_menus_tree');
        Cache::forget('global_portal_permissions');
        Cache::forget('role-allowed-routes-1');
        Cache::forget('role-allowed-routes-4');
        Cache::forget('role-allowed-routes-9');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
    }
};
