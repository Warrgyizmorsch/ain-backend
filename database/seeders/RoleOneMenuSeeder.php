<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use App\Models\EmailConfiguration;

class RoleOneMenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Configuring Setting Plugins, Standalone WhatsApp Main Menu, and Emails Menu...');

        // =============================================================
        // 1. SETTING MENU (Menu ID 2)
        // =============================================================
        DB::table('menu')->where('id', 2)->update([
            'menu_name' => 'Setting',
            'icon_class' => 'fa fa-gear',
            'show_menu' => 'Y',
            'routes' => 'menus',
            'updated_at' => now(),
        ]);

        $settingItems = [
            ['name' => 'Menu', 'routes' => 'menus', 'sort_order' => 1],
            ['name' => 'submenus', 'routes' => 'submenu', 'sort_order' => 2],
            ['name' => 'User Right', 'routes' => 'userright', 'sort_order' => 3],
            ['name' => 'WhatsApp Plugin', 'routes' => 'whatsapp/settings', 'sort_order' => 4],
            ['name' => 'Call Plugin', 'routes' => 'admin/plugins', 'sort_order' => 5],
            ['name' => 'Email Plugin', 'routes' => 'emails/settings', 'sort_order' => 6],
        ];

        // Clean up Label Master from Setting (parent_id = 2 / menus_id = 2)
        DB::table('menu')->where('parent_id', 2)->where('routes', 'labels')->delete();
        DB::table('submenus')->where('menus_id', 2)->where('routes', 'labels')->delete();

        foreach ($settingItems as $item) {
            // Sync in 'menu' table (as child with parent_id = 2)
            DB::table('menu')->updateOrInsert(
                ['parent_id' => 2, 'routes' => $item['routes']],
                [
                    'menu_name' => $item['name'],
                    'sort_order' => $item['sort_order'],
                    'show_menu' => 'Y',
                    'icon_class' => 'fa fa-gear',
                    'updated_at' => now()
                ]
            );

            // Sync in 'submenus' table (with menus_id = 2)
            DB::table('submenus')->updateOrInsert(
                ['menus_id' => 2, 'routes' => $item['routes']],
                [
                    'sub_menu_name' => $item['name'],
                    'sort_order' => $item['sort_order'],
                    'show' => 'Y',
                    'updated_at' => now()
                ]
            );
        }

        // =============================================================
        // 1.1 MASTERS MENU (Menu ID 3) - Add Label Master
        // =============================================================
        DB::table('menu')->updateOrInsert(
            ['parent_id' => 3, 'routes' => 'labels'],
            [
                'menu_name' => 'Label Master',
                'sort_order' => 15,
                'show_menu' => 'Y',
                'icon_class' => 'fa fa-tags',
                'updated_at' => now()
            ]
        );

        DB::table('submenus')->updateOrInsert(
            ['menus_id' => 3, 'routes' => 'labels'],
            [
                'sub_menu_name' => 'Label Master',
                'sort_order' => 15,
                'show' => 'Y',
                'updated_at' => now()
            ]
        );

        // Seed default labels if none exist
        if (\App\Models\WhatsappChatLabel::count() === 0) {
            $defaultLabels = [
                ['name' => 'Support', 'color' => '#3454d1'],
                ['name' => 'Orders', 'color' => '#10b981'],
                ['name' => 'Follow up', 'color' => '#f59e0b'],
                ['name' => 'Urgent', 'color' => '#ef4444'],
                ['name' => 'VIP Client', 'color' => '#8b5cf6'],
                ['name' => 'Payment Issue', 'color' => '#f97316'],
            ];
            foreach ($defaultLabels as $dl) {
                \App\Models\WhatsappChatLabel::create([
                    'name' => $dl['name'],
                    'color' => $dl['color'],
                    'created_by' => 1,
                ]);
            }
        }

        // =============================================================
        // 2. WHATSAPP MAIN MENU (Menu ID 24) - Single Direct Menu (NO SUBMENUS)
        // =============================================================
        DB::table('menu')->where('id', 24)->update([
            'menu_name' => 'WhatsApp',
            'icon_class' => 'fa fa-whatsapp',
            'show_menu' => 'Y',
            'parent_id' => null,
            'routes' => 'whatsapp/chat',
            'sort_order' => 22,
            'updated_at' => now(),
        ]);

        // Remove ALL submenus and child menus for WhatsApp so it's a direct standalone main menu
        DB::table('menu')->where('parent_id', 24)->delete();
        DB::table('submenus')->where('menus_id', 24)->delete();

        // Hide legacy Menu 31
        DB::table('menu')->where('id', 31)->update(['show_menu' => 'N', 'updated_at' => now()]);

        // =============================================================
        // 3. EMAILS MAIN MENU - Single Direct Main Menu (NO SUBMENUS)
        // =============================================================
        $emailMenu = DB::table('menu')->where('routes', 'emails')->whereNull('parent_id')->first();
        if (!$emailMenu) {
            $emailMenuId = DB::table('menu')->insertGetId([
                'menu_name' => 'Emails',
                'icon_class' => 'fa fa-envelope',
                'show_menu' => 'Y',
                'routes' => 'emails',
                'sort_order' => 23,
                'parent_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } else {
            $emailMenuId = $emailMenu->id;
            DB::table('menu')->where('id', $emailMenuId)->update([
                'menu_name' => 'Emails',
                'icon_class' => 'fa fa-envelope',
                'show_menu' => 'Y',
                'parent_id' => null,
                'routes' => 'emails',
                'sort_order' => 23,
                'updated_at' => now(),
            ]);
        }

        // Remove ALL submenus and child menus for Emails so it's a direct standalone main menu
        DB::table('menu')->where('parent_id', $emailMenuId)->delete();
        DB::table('submenus')->where('menus_id', $emailMenuId)->delete();

        // =============================================================
        // 4. SEED INITIAL EMAIL CONFIGS (Account switching done inside Inbox UI)
        // =============================================================
        EmailConfiguration::updateOrCreate(
            ['email_address' => 'anshulsuthar.warrgyizmorsch@gmail.com'],
            [
                'name' => 'App',
                'from_name' => 'Anshul',
                'driver' => 'smtp',
                'host' => 'smtp.gmail.com',
                'port' => 587,
                'encryption' => 'tls',
                'username' => 'anshulsuthar.warrgyizmorsch@gmail.com',
                'password' => 'eyfntqaxwfeqofhq',
                'incoming_protocol' => 'imap',
                'incoming_host' => 'imap.gmail.com',
                'incoming_port' => 993,
                'incoming_encryption' => 'ssl',
                'incoming_username' => 'anshulsuthar.warrgyizmorsch@gmail.com',
                'incoming_password' => 'eyfntqaxwfeqofhq',
                'is_default' => true,
                'is_active' => true,
                'sort_order' => 1,
            ]
        );

        // Ensure other accounts are not set as default
        EmailConfiguration::where('email_address', '!=', 'anshulsuthar.warrgyizmorsch@gmail.com')
            ->update(['is_default' => false]);

        EmailConfiguration::syncEmailSubmenus();

        // =============================================================
        // 5. UPDATE PERMISSIONS FOR ALL ROLES
        // =============================================================
        $allMenuIds = DB::table('menu')->pluck('id')->map(fn($id) => (string) $id)->toArray();
        $allSubmenuIds = DB::table('submenus')->pluck('id')->map(fn($id) => (string) $id)->toArray();

        // Role 1 (Super Admin)
        DB::table('permission')->updateOrInsert(
            ['role_id' => 1],
            [
                'menu_id' => json_encode(array_values(array_unique($allMenuIds))),
                'submenu_id' => json_encode(array_values(array_unique($allSubmenuIds))),
                'updated_at' => now(),
            ]
        );

        $settingAndPluginMenuIds = DB::table('menu')
            ->where(function ($q) {
                $q->where('id', 2)
                  ->orWhere('parent_id', 2)
                  ->orWhere('id', 24)
                  ->orWhere('id', 132)
                  ->orWhere('parent_id', 132);
            })
            ->pluck('id')
            ->map(fn($id) => (string) $id)
            ->toArray();

        $settingAndPluginSubmenuIds = DB::table('submenus')
            ->whereIn('menus_id', [2, 132])
            ->pluck('id')
            ->map(fn($id) => (string) $id)
            ->toArray();

        $masterSubmenuIds = DB::table('submenus')
            ->where('menus_id', 3)
            ->pluck('id')
            ->map(fn($id) => (string) $id)
            ->toArray();

        $allOtherPerms = DB::table('permission')->get();
        foreach ($allOtherPerms as $perm) {
            $mIds = json_decode($perm->menu_id, true) ?? [];
            $sIds = json_decode($perm->submenu_id, true) ?? [];
            $changed = false;

            if (in_array("2", array_map('strval', $mIds)) || in_array(2, $mIds)) {
                $mIds = array_unique(array_merge($mIds, $settingAndPluginMenuIds));
                $sIds = array_unique(array_merge($sIds, $settingAndPluginSubmenuIds));
                $changed = true;
            }

            if (in_array("3", array_map('strval', $mIds)) || in_array(3, $mIds)) {
                $sIds = array_unique(array_merge($sIds, $masterSubmenuIds));
                $changed = true;
            }

            if ($changed) {
                DB::table('permission')->where('id', $perm->id)->update([
                    'menu_id' => json_encode(array_values(array_map('strval', $mIds))),
                    'submenu_id' => json_encode(array_values(array_map('strval', $sIds))),
                    'updated_at' => now(),
                ]);
            }
        }

        // =============================================================
        // 6. CLEAR CACHES
        // =============================================================
        Cache::flush();
        $this->command->info('Seeding & permission sync fully completed!');
    }
}
