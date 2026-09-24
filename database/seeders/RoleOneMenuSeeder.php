<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
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
        // Dashboard must be the first direct menu for Super Admin.
        DB::table('menu')->where('id', 1)->update([
            'menu_name' => 'Dashboard',
            'icon_class' => 'fas fa-tachometer-alt',
            'show_menu' => 'Y',
            'routes' => 'dashboard',
            'sort_order' => 1,
            'updated_at' => now(),
        ]);

        DB::table('menu')->where('id', 2)->update([
            'menu_name' => 'Setting',
            'icon_class' => 'fa fa-gear',
            'show_menu' => 'Y',
            'routes' => 'menus',
            'sort_order' => 99,
            'updated_at' => now(),
        ]);

        // Setting must remain immediately above Other.
        DB::table('menu')->where('id', 44)->update([
            'sort_order' => 100,
            'updated_at' => now(),
        ]);

        $settingItems = [
            ['name' => 'Menu', 'routes' => 'menus', 'sort_order' => 1],
            ['name' => 'submenus', 'routes' => 'submenu', 'sort_order' => 2],
            ['name' => 'User Right', 'routes' => 'userright', 'sort_order' => 3],
            ['name' => 'WhatsApp Settings', 'routes' => 'whatsapp/settings', 'sort_order' => 4],
            ['name' => 'Call Plugin', 'routes' => 'admin/plugins', 'sort_order' => 5],
            ['name' => 'Email Settings', 'routes' => 'emails/settings', 'sort_order' => 6],
        ];

        // Clean up Label Master from Setting (parent_id = 2 / menus_id = 2)
        DB::table('menu')->where('parent_id', 2)->where('routes', 'labels')->delete();
        DB::table('submenus')->where('menus_id', 2)->where('routes', 'labels')->delete();

        // Clean up separate Next2Call routes from Setting so only single "Call Plugin" appears
        DB::table('menu')->where('parent_id', 2)->where('routes', 'admin/plugins/next2call')->delete();
        DB::table('submenus')->where('menus_id', 2)->where('routes', 'admin/plugins/next2call')->delete();

        // Update legacy names for admin/plugins (e.g. 'Twilio Calling', 'Twilio Plugin', 'Plugin Settings') to 'Call Plugin'
        DB::table('menu')->where('parent_id', 2)->where('routes', 'admin/plugins')->update(['menu_name' => 'Call Plugin', 'updated_at' => now()]);
        DB::table('submenus')->where('menus_id', 2)->where('routes', 'admin/plugins')->update(['sub_menu_name' => 'Call Plugin', 'updated_at' => now()]);
        DB::table('submenus')->where('menus_id', 2)->whereIn('sub_menu_name', ['Twilio Calling', 'Twilio Plugin', 'Plugin Settings'])->update(['sub_menu_name' => 'Call Plugin', 'routes' => 'admin/plugins', 'updated_at' => now()]);
        DB::table('menu')->where('parent_id', 2)->whereIn('menu_name', ['Twilio Calling', 'Twilio Plugin', 'Plugin Settings'])->update(['menu_name' => 'Call Plugin', 'routes' => 'admin/plugins', 'updated_at' => now()]);

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
        // Masters is a standalone main menu, immediately above Setting.
        DB::table('menu')->where('id', 3)->update([
            'parent_id' => null,
            'menu_name' => 'Masters',
            'icon_class' => 'fas fa-gem',
            'show_menu' => 'Y',
            'routes' => 'master',
            'sort_order' => 98,
            'updated_at' => now(),
        ]);

        // Masters uses direct submenus. Remove the older child-menu duplicate
        // so all existing Master entries and Label Master render together.
        DB::table('menu')->where('parent_id', 3)->where('routes', 'labels')->delete();

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
            'icon_class' => 'fab fa-whatsapp',
            'show_menu' => 'Y',
            'parent_id' => null,
            'routes' => 'whatsapp/chat',
            'sort_order' => 2,
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
                'sort_order' => 3,
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
                'sort_order' => 3,
                'updated_at' => now(),
            ]);
        }

        // Remove ALL submenus and child menus for Emails so it's a direct standalone main menu
        DB::table('menu')->where('parent_id', $emailMenuId)->delete();
        DB::table('submenus')->where('menus_id', $emailMenuId)->delete();

        // =============================================================
        // 3.1 CALL HISTORY MAIN MENU - Single Direct Main Menu (NO SUBMENUS)
        // =============================================================
        $callHistoryMenu = DB::table('menu')->where('routes', 'call-history')->whereNull('parent_id')->first();
        if (!$callHistoryMenu) {
            $callHistoryMenuId = DB::table('menu')->insertGetId([
                'menu_name' => 'Call History',
                'icon_class' => 'fa fa-phone',
                'show_menu' => 'Y',
                'routes' => 'call-history',
                'sort_order' => 4,
                'parent_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } else {
            $callHistoryMenuId = $callHistoryMenu->id;
            DB::table('menu')->where('id', $callHistoryMenuId)->update([
                'menu_name' => 'Call History',
                'icon_class' => 'fa fa-phone',
                'show_menu' => 'Y',
                'parent_id' => null,
                'routes' => 'call-history',
                'sort_order' => 4,
                'updated_at' => now(),
            ]);
        }

        // Remove ALL submenus and child menus for Call History so it's a direct standalone main menu
        DB::table('menu')->where('parent_id', $callHistoryMenuId)->delete();
        DB::table('submenus')->where('menus_id', $callHistoryMenuId)->delete();

        // =============================================================
        // 4. SEED INITIAL EMAIL CONFIGS (Assignment Help & Write Email)
        // =============================================================
        // Remove old / legacy accounts (including 'App' / anshulsuthar)
        EmailConfiguration::whereNotIn('email_address', [
            'assignmentinneedhelp@gmail.com',
            'order@assignnmentinneed.com'
        ])->delete();

        EmailConfiguration::updateOrCreate(
            ['email_address' => 'assignmentinneedhelp@gmail.com'],
            [
                'name' => 'Writer',
                'from_name' => 'Assignment In Need',
                'driver' => 'smtp',
                'host' => 'smtp.gmail.com',
                'port' => 465,
                'encryption' => 'ssl',
                'username' => 'assignmentinneedhelp@gmail.com',
                'password' => 'bguttdxpipzouwzm',
                'incoming_protocol' => 'imap',
                'incoming_host' => 'imap.gmail.com',
                'incoming_port' => 993,
                'incoming_encryption' => 'ssl',
                'incoming_username' => 'assignmentinneedhelp@gmail.com',
                'incoming_password' => 'bguttdxpipzouwzm',
                'is_default' => true,
                'is_active' => true,
                'sort_order' => 1,
            ]
        );

        EmailConfiguration::updateOrCreate(
            ['email_address' => 'order@assignnmentinneed.com'],
            [
                'name' => 'Client',
                'from_name' => 'Assignment In Need',
                'driver' => 'smtp',
                'host' => 'smtp.gmail.com',
                'port' => 465,
                'encryption' => 'ssl',
                'username' => 'order@assignnmentinneed.com',
                'password' => 'nnrjmhorihcfgwyw',
                'incoming_protocol' => 'imap',
                'incoming_host' => 'imap.gmail.com',
                'incoming_port' => 993,
                'incoming_encryption' => 'ssl',
                'incoming_username' => 'order@assignnmentinneed.com',
                'incoming_password' => 'nnrjmhorihcfgwyw',
                'is_default' => false,
                'is_active' => true,
                'sort_order' => 2,
            ]
        );

        EmailConfiguration::where('email_address', '!=', 'assignmentinneedhelp@gmail.com')
            ->update(['is_default' => false]);

        EmailConfiguration::syncEmailSubmenus();

        // syncEmailSubmenus also supports older installations; enforce this
        // seeder's final admin-sidebar labels and ordering after that sync.
        DB::table('menu')->where('id', $emailMenuId)->update([
            'sort_order' => 3,
            'updated_at' => now(),
        ]);
        DB::table('menu')
            ->where('parent_id', 2)
            ->where('routes', 'emails/settings')
            ->update([
                'menu_name' => 'Email Settings',
                'sort_order' => 6,
                'updated_at' => now(),
            ]);
        DB::table('submenus')
            ->where('menus_id', 2)
            ->where('routes', 'emails/settings')
            ->update([
                'sub_menu_name' => 'Email Settings',
                'sort_order' => 6,
                'updated_at' => now(),
            ]);

        // =============================================================
        // 4.1 PLUGIN SETTINGS (Twilio Voice & Next2Call Softphone)
        // =============================================================
        if (Schema::hasTable('plugin_settings')) {
            DB::table('plugin_settings')->updateOrInsert(
                ['plugin_key' => 'twilio_call'],
                [
                    'name' => 'Twilio Voice Call',
                    'category' => 'communication',
                    'description' => 'Bridge calls between agents and customers directly from the Orders page using Twilio Voice API & WebRTC Dialer.',
                    'is_active' => false,
                    'settings' => json_encode([
                        'account_sid' => '',
                        'auth_token' => '',
                        'twilio_number' => '',
                        'api_key_sid' => '',
                        'api_secret' => '',
                        'twiml_app_sid' => '',
                        'default_agent_number' => '',
                        'call_mode' => 'webrtc',
                        'record_calls' => false,
                    ]),
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );

            DB::table('plugin_settings')->updateOrInsert(
                ['plugin_key' => 'next2call'],
                [
                    'name' => 'Next2Call Softphone',
                    'category' => 'communication',
                    'description' => 'Direct in-browser WebRTC softphone calling & click-to-dial powered by Next2Call Ringfy PBX.',
                    'is_active' => true,
                    'settings' => json_encode([
                        'user_id' => '10101',
                        'password' => 'T2d8d1r5P6x0T8O8iUq',
                        'sip_domain' => 'ringfy.next2call.com',
                        'api_base_url' => 'https://ringfy.next2call.com',
                        'click_to_dial_path' => '/softphone/Phone/click-to-dial.html',
                    ]),
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );
        }

        // Keep the existing Break Time Report page available from the
        // Reports group for Super Admin. Other roles are not granted this
        // submenu by this seeder.
        $reportsMenuId = DB::table('menu')
            ->where(function ($query) {
                $query->where('routes', 'reports')
                    ->orWhere('menu_name', 'Reports');
            })
            ->whereNull('parent_id')
            ->value('id');

        if ($reportsMenuId) {
            DB::table('submenus')->updateOrInsert(
                [
                    'menus_id' => $reportsMenuId,
                    'routes' => 'break-time-report',
                ],
                [
                    'sub_menu_name' => 'Break Time Report',
                    'sort_order' => 12,
                    'show' => 'Y',
                    'updated_at' => now(),
                ]
            );
        }

        // This legacy report remains routable when needed, but it must not
        // appear in the sidebar menu.
        $hiddenWriterReportSubmenuIds = DB::table('submenus')
            ->where('routes', 'writer-additional-word-count-report')
            ->pluck('id')
            ->map(fn($id) => (string) $id)
            ->toArray();

        DB::table('submenus')
            ->whereIn('id', $hiddenWriterReportSubmenuIds)
            ->delete();

        if ($hiddenWriterReportSubmenuIds) {
            DB::table('permission')->select('id', 'submenu_id')->get()->each(
                function ($permission) use ($hiddenWriterReportSubmenuIds) {
                    $submenuIds = json_decode($permission->submenu_id ?: '[]', true);
                    $submenuIds = is_array($submenuIds) ? $submenuIds : [];
                    $submenuIds = array_values(array_filter(
                        $submenuIds,
                        fn($id) => !in_array((string) $id, $hiddenWriterReportSubmenuIds, true)
                    ));

                    DB::table('permission')->where('id', $permission->id)->update([
                        'submenu_id' => json_encode($submenuIds),
                        'updated_at' => now(),
                    ]);
                }
            );
        }

        // Reserve the first four top-level positions exclusively for
        // Dashboard, WhatsApp, Emails, and Call History. Keep every other menu after them.
        DB::table('menu')
            ->whereNull('parent_id')
            ->whereNotIn('id', [1, 24, $emailMenuId, $callHistoryMenuId])
            ->where('sort_order', '<', 5)
            ->update([
                'sort_order' => 5,
                'updated_at' => now(),
            ]);

        // =============================================================
        // 5. UPDATE PERMISSIONS FOR ALL ROLES
        // =============================================================
        // These legacy direct links are intentionally excluded from Super Admin.
        // Their relevant functionality remains available through the existing
        // grouped menus (for example Masters/Writer inside Other).
        $adminHiddenTopLevelMenuIds = [14, 17, 23]; // College, Qc Sheet, Ticket Sheet
        $allMenuIds = DB::table('menu')
            ->whereNotIn('id', $adminHiddenTopLevelMenuIds)
            ->pluck('id')
            ->map(fn($id) => (string) $id)
            ->toArray();
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
            ->where(function ($q) use ($callHistoryMenuId) {
                $q->where('id', 2)
                  ->orWhere('parent_id', 2)
                  ->orWhere('id', 24)
                  ->orWhere('id', $callHistoryMenuId)
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
