<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class PluginMenuSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        // 1. Ensure "Setting" parent menu exists (ID 2 in current setup)
        $settingMenu = DB::table('menu')->where('id', 2)->first();
        if (!$settingMenu) {
            $settingMenu = DB::table('menu')->where('menu_name', 'like', '%Setting%')->first();
        }

        $settingMenuId = $settingMenu ? $settingMenu->id : 2;

        // 2. Insert or update Submenu for Plugins under Setting
        $existingSubmenu = DB::table('submenus')
            ->where('menus_id', $settingMenuId)
            ->where('routes', 'admin/plugins')
            ->first();

        if ($existingSubmenu) {
            $pluginSubmenuId = $existingSubmenu->id;
            DB::table('submenus')->where('id', $pluginSubmenuId)->update([
                'sub_menu_name' => 'Plugins',
                'menus_id' => $settingMenuId,
                'routes' => 'admin/plugins',
                'show' => 'Y',
                'sort_order' => 10,
                'updated_at' => $now,
            ]);
        } else {
            $maxSubmenuId = (int) DB::table('submenus')->max('id');
            $newSubmenuId = max($maxSubmenuId + 1, 100);

            DB::table('submenus')->insert([
                'id' => $newSubmenuId,
                'sub_menu_name' => 'Plugins',
                'menus_id' => $settingMenuId,
                'routes' => 'admin/plugins',
                'sort_order' => 10,
                'show' => 'Y',
                'created_at' => $now,
                'updated_at' => $now,
            ]);
            $pluginSubmenuId = $newSubmenuId;
        }

        // 3. Grant permission to Super Admin (role_id: 1)
        $permission = DB::table('permission')->where('role_id', 1)->first();
        $menuIds = $permission ? json_decode($permission->menu_id, true) : [];
        $submenuIds = $permission ? json_decode($permission->submenu_id, true) : [];

        $menuIds = is_array($menuIds) ? $menuIds : [];
        $submenuIds = is_array($submenuIds) ? $submenuIds : [];

        if (!in_array($settingMenuId, array_map('intval', $menuIds), true)) {
            $menuIds[] = $settingMenuId;
        }

        if (!in_array($pluginSubmenuId, array_map('intval', $submenuIds), true)) {
            $submenuIds[] = $pluginSubmenuId;
        }

        DB::table('permission')->updateOrInsert(
            ['role_id' => 1],
            [
                'menu_id' => json_encode(array_values(array_unique($menuIds))),
                'submenu_id' => json_encode(array_values(array_unique($submenuIds))),
                'updated_at' => $now,
                'created_at' => $permission?->created_at ?? $now,
            ]
        );

        // 4. Seed default plugin entry in plugin_settings table
        if (Schema::hasTable('plugin_settings')) {
            DB::table('plugin_settings')->updateOrInsert(
                ['plugin_key' => 'twilio_call'],
                [
                    'name' => 'Twilio Voice Call',
                    'category' => 'communication',
                    'description' => 'Bridge calls between agents and customers directly from the Orders page using Twilio Voice API.',
                    'is_active' => false,
                    'settings' => json_encode([
                        'account_sid' => '',
                        'auth_token' => '',
                        'twilio_number' => '',
                        'default_agent_number' => '',
                        'call_mode' => 'bridge', // 'bridge' (calls agent first then dials customer) or 'direct'
                        'record_calls' => false,
                    ]),
                    'updated_at' => $now,
                    'created_at' => $now,
                ]
            );
        }
    }
}
