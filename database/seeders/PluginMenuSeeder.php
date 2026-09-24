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

        // 2. Remove separate Next2Call submenu if previously created so only single "Call Plugin" submenu exists under Setting
        DB::table('submenus')
            ->where('menus_id', $settingMenuId)
            ->where('routes', 'admin/plugins/next2call')
            ->delete();

        // 3. Insert or update single Submenu "Call Plugin" under Setting
        $existingSubmenu = DB::table('submenus')
            ->where('menus_id', $settingMenuId)
            ->where(function ($q) {
                $q->where('routes', 'admin/plugins')
                  ->orWhere('sub_menu_name', 'Plugin Settings')
                  ->orWhere('sub_menu_name', 'Call Plugin');
            })
            ->first();

        if ($existingSubmenu) {
            $pluginSubmenuId = $existingSubmenu->id;
            DB::table('submenus')->where('id', $pluginSubmenuId)->update([
                'sub_menu_name' => 'Call Plugin',
                'menus_id'      => $settingMenuId,
                'routes'        => 'admin/plugins',
                'show'          => 'Y',
                'sort_order'    => 10,
                'updated_at'    => $now,
            ]);
        } else {
            $maxSubmenuId = (int) DB::table('submenus')->max('id');
            $newSubmenuId = max($maxSubmenuId + 1, 100);

            DB::table('submenus')->insert([
                'id'            => $newSubmenuId,
                'sub_menu_name' => 'Call Plugin',
                'menus_id'      => $settingMenuId,
                'routes'        => 'admin/plugins',
                'sort_order'    => 10,
                'show'          => 'Y',
                'created_at'    => $now,
                'updated_at'    => $now,
            ]);
            $pluginSubmenuId = $newSubmenuId;
        }

        // 4. Grant permission to Super Admin (role_id: 1)
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
                'menu_id'    => json_encode(array_values(array_unique($menuIds))),
                'submenu_id' => json_encode(array_values(array_unique($submenuIds))),
                'updated_at' => $now,
                'created_at' => $permission?->created_at ?? $now,
            ]
        );

        // 5. Seed Twilio & Next2Call plugin entries in plugin_settings table
        if (Schema::hasTable('plugin_settings')) {
            $existingTwilio = DB::table('plugin_settings')->where('plugin_key', 'twilio_call')->first();
            if (!$existingTwilio) {
                DB::table('plugin_settings')->insert([
                    'plugin_key'  => 'twilio_call',
                    'name'        => 'Twilio Voice Call',
                    'category'    => 'communication',
                    'description' => 'Bridge voice calls between agents and customers directly from the Orders page using Twilio Voice API & WebRTC Dialer.',
                    'is_active'   => true,
                    'settings'    => json_encode([
                        'account_sid'          => env('TWILIO_ACCOUNT_SID', env('TWILIO_SID', 'ACce3d9633593afbeda1054ac03f555ab3')),
                        'auth_token'           => env('TWILIO_AUTH_TOKEN', env('TWILIO_TOKEN', '')),
                        'twilio_number'        => env('TWILIO_NUMBER', env('TWILIO_PHONE_NUMBER', env('TWILIO_FROM', '+15054963739'))),
                        'api_key_sid'          => env('TWILIO_API_KEY_SID', env('TWILIO_API_KEY', 'SK68c36d375a7551364289a1b85a83e38b')),
                        'api_secret'           => env('TWILIO_API_SECRET', env('TWILIO_SECRET', 'rNXWstz1t72NSD4n60eT1uz2mZZLzfWe')),
                        'twiml_app_sid'        => env('TWILIO_TWIML_APP_SID', env('TWILIO_APP_SID', 'APde9388f580c06d9c737fbc995a3601a7')),
                        'default_agent_number' => env('TWILIO_AGENT_NUMBER', ''),
                        'call_mode'            => 'webrtc',
                        'record_calls'         => false,
                    ]),
                    'updated_at'  => $now,
                    'created_at'  => $now,
                ]);
            } else {
                $twSettings = json_decode($existingTwilio->settings ?? '[]', true) ?: [];
                $needsTwUpdate = false;

                if (empty($twSettings['account_sid'])) {
                    $twSettings['account_sid'] = env('TWILIO_ACCOUNT_SID', env('TWILIO_SID', 'ACce3d9633593afbeda1054ac03f555ab3'));
                    $needsTwUpdate = true;
                }
                if (empty($twSettings['auth_token']) && (env('TWILIO_AUTH_TOKEN') || env('TWILIO_TOKEN'))) {
                    $twSettings['auth_token'] = env('TWILIO_AUTH_TOKEN', env('TWILIO_TOKEN'));
                    $needsTwUpdate = true;
                }
                if (empty($twSettings['twilio_number'])) {
                    $twSettings['twilio_number'] = env('TWILIO_NUMBER', env('TWILIO_PHONE_NUMBER', env('TWILIO_FROM', '+15054963739')));
                    $needsTwUpdate = true;
                }
                if (empty($twSettings['api_key_sid'])) {
                    $twSettings['api_key_sid'] = env('TWILIO_API_KEY_SID', env('TWILIO_API_KEY', 'SK68c36d375a7551364289a1b85a83e38b'));
                    $needsTwUpdate = true;
                }
                if (empty($twSettings['api_secret'])) {
                    $twSettings['api_secret'] = env('TWILIO_API_SECRET', env('TWILIO_SECRET', 'rNXWstz1t72NSD4n60eT1uz2mZZLzfWe'));
                    $needsTwUpdate = true;
                }
                if (empty($twSettings['twiml_app_sid'])) {
                    $twSettings['twiml_app_sid'] = env('TWILIO_TWIML_APP_SID', env('TWILIO_APP_SID', 'APde9388f580c06d9c737fbc995a3601a7'));
                    $needsTwUpdate = true;
                }

                if ($needsTwUpdate) {
                    DB::table('plugin_settings')->where('plugin_key', 'twilio_call')->update([
                        'settings' => json_encode($twSettings),
                        'updated_at' => $now,
                    ]);
                }
            }

            $existingN2c = DB::table('plugin_settings')->where('plugin_key', 'next2call')->first();
            if (!$existingN2c) {
                DB::table('plugin_settings')->insert([
                    'plugin_key'  => 'next2call',
                    'name'        => 'Next2Call Softphone',
                    'category'    => 'communication',
                    'description' => 'Direct in-browser WebRTC softphone calling & click-to-dial powered by Next2Call Ringfy PBX.',
                    'is_active'   => true,
                    'settings'    => json_encode([
                        'user_id'            => '10101',
                        'password'           => 'T2d8d1r5P6x0T8O8iUq',
                        'sip_domain'         => 'ringfy.next2call.com',
                        'api_base_url'       => 'https://ringfy.next2call.com',
                        'click_to_dial_path' => '/softphone/Phone/click-to-dial.html',
                    ]),
                    'updated_at'  => $now,
                    'created_at'  => $now,
                ]);
            }
        }
    }
}
