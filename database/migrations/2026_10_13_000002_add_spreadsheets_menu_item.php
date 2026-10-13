<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('menu')) {
            $existing = DB::table('menu')->where('routes', 'spreadsheets')->first();
            if (!$existing) {
                $menuId = DB::table('menu')->insertGetId([
                    'menu_name' => 'Excel Sheets',
                    'icon_class' => 'fa fa-file-excel',
                    'show_menu' => 'Y',
                    'routes' => 'spreadsheets',
                    'sort_order' => 5,
                    'parent_id' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } else {
                $menuId = $existing->id;
                DB::table('menu')->where('id', $menuId)->update([
                    'menu_name' => 'Excel Sheets',
                    'icon_class' => 'fa fa-file-excel',
                    'show_menu' => 'Y',
                    'sort_order' => 5,
                    'parent_id' => null,
                    'updated_at' => now(),
                ]);
            }

            // Grant permission to Super Admin (role_id 1) and any existing roles
            if (Schema::hasTable('permission')) {
                $adminPerm = DB::table('permission')->where('role_id', 1)->first();
                if ($adminPerm) {
                    $menuIds = json_decode($adminPerm->menu_id, true) ?? [];
                    $menuIdsStr = array_map('strval', $menuIds);
                    if (!in_array((string)$menuId, $menuIdsStr, true)) {
                        $menuIdsStr[] = (string)$menuId;
                        DB::table('permission')->where('role_id', 1)->update([
                            'menu_id' => json_encode(array_values(array_unique($menuIdsStr))),
                            'updated_at' => now(),
                        ]);
                    }
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('menu')) {
            $menu = DB::table('menu')->where('routes', 'spreadsheets')->first();
            if ($menu) {
                DB::table('menu')->where('id', $menu->id)->delete();
            }
        }
    }
};
