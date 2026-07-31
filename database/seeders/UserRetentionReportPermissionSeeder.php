<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserRetentionReportPermissionSeeder extends Seeder
{
    public function run(): void
    {
        $reportSubmenus = [
            [
                'name' => 'User Retention Report',
                'route' => 'user-retention-report',
                'sort_order' => 6,
            ],
            [
                'name' => 'Feedback List',
                'route' => 'feedback-list',
                'sort_order' => 7,
            ],
        ];

        $submenuIds = [];

        foreach ($reportSubmenus as $reportSubmenu) {
            $submenuId = DB::table('submenus')->where('routes', $reportSubmenu['route'])->value('id');

            if (!$submenuId) {
                $submenuId = DB::table('submenus')->insertGetId([
                    'sub_menu_name' => $reportSubmenu['name'],
                    'menus_id' => 40,
                    'routes' => $reportSubmenu['route'],
                    'sort_order' => $reportSubmenu['sort_order'],
                    'show' => 'Y',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } else {
                DB::table('submenus')->where('id', $submenuId)->update([
                    'sub_menu_name' => $reportSubmenu['name'],
                    'menus_id' => 40,
                    'sort_order' => $reportSubmenu['sort_order'],
                    'show' => 'Y',
                    'updated_at' => now(),
                ]);
            }

            $submenuIds[] = (int) $submenuId;
        }

        $permission = DB::table('permission')->where('role_id', 1)->first();
        $existingSubmenuIds = $permission ? json_decode($permission->submenu_id, true) : [];
        $menuIds = $permission ? json_decode($permission->menu_id, true) : [];

        $existingSubmenuIds = is_array($existingSubmenuIds) ? $existingSubmenuIds : [];
        $menuIds = is_array($menuIds) ? $menuIds : [];

        foreach ($submenuIds as $submenuId) {
            if (!in_array($submenuId, array_map('intval', $existingSubmenuIds), true)) {
                $existingSubmenuIds[] = $submenuId;
            }
        }

        if (!in_array(40, array_map('intval', $menuIds), true)) {
            $menuIds[] = 40;
        }

        DB::table('permission')->updateOrInsert(
            ['role_id' => 1],
            [
                'menu_id' => json_encode(array_values($menuIds)),
                'submenu_id' => json_encode(array_values($existingSubmenuIds)),
                'updated_at' => now(),
                'created_at' => $permission?->created_at ?? now(),
            ]
        );
    }
}
