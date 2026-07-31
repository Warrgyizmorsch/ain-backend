<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserRetentionReportPermissionSeeder extends Seeder
{
    public function run(): void
    {
        $submenuId = DB::table('submenus')->where('routes', 'user-retention-report')->value('id');

        if (!$submenuId) {
            $submenuId = DB::table('submenus')->insertGetId([
                'sub_menu_name' => 'User Retention Report',
                'menus_id' => 40,
                'routes' => 'user-retention-report',
                'sort_order' => 6,
                'show' => 'Y',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } else {
            DB::table('submenus')->where('id', $submenuId)->update([
                'sub_menu_name' => 'User Retention Report',
                'menus_id' => 40,
                'show' => 'Y',
                'updated_at' => now(),
            ]);
        }

        $permission = DB::table('permission')->where('role_id', 1)->first();
        $submenuIds = $permission ? json_decode($permission->submenu_id, true) : [];
        $menuIds = $permission ? json_decode($permission->menu_id, true) : [];

        $submenuIds = is_array($submenuIds) ? $submenuIds : [];
        $menuIds = is_array($menuIds) ? $menuIds : [];

        if (!in_array((int) $submenuId, array_map('intval', $submenuIds), true)) {
            $submenuIds[] = (int) $submenuId;
        }

        if (!in_array(40, array_map('intval', $menuIds), true)) {
            $menuIds[] = 40;
        }

        DB::table('permission')->updateOrInsert(
            ['role_id' => 1],
            [
                'menu_id' => json_encode(array_values($menuIds)),
                'submenu_id' => json_encode(array_values($submenuIds)),
                'updated_at' => now(),
                'created_at' => $permission?->created_at ?? now(),
            ]
        );
    }
}
