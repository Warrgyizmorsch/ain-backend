<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class EmailConfiguration extends Model
{
    use HasFactory;

    protected $table = 'email_configurations';

    protected $fillable = [
        'name',
        'email_address',
        'from_name',
        'driver',
        'host',
        'port',
        'encryption',
        'username',
        'password',
        'incoming_protocol',
        'incoming_host',
        'incoming_port',
        'incoming_encryption',
        'incoming_username',
        'incoming_password',
        'settings',
        'is_default',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'settings' => 'array',
        'is_default' => 'boolean',
        'is_active' => 'boolean',
        'port' => 'integer',
        'incoming_port' => 'integer',
        'sort_order' => 'integer',
        'password' => 'encrypted',
        'incoming_password' => 'encrypted',
    ];

    /**
     * Auto sync standalone Emails main menu (no submenus; account switching is in the UI).
     */
    public static function syncEmailSubmenus()
    {
        try {
            $emailMenu = DB::table('menu')->where('routes', 'emails')->whereNull('parent_id')->first();
            if (!$emailMenu) {
                $menuId = DB::table('menu')->insertGetId([
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
                $menuId = $emailMenu->id;
                DB::table('menu')->where('id', $menuId)->update([
                    'menu_name' => 'Emails',
                    'icon_class' => 'fa fa-envelope',
                    'show_menu' => 'Y',
                    'parent_id' => null,
                    'routes' => 'emails',
                    'sort_order' => 23,
                    'updated_at' => now(),
                ]);
            }

            // Remove all child menus and submenus under Emails so it remains a single direct main menu
            DB::table('submenus')->where('menus_id', $menuId)->delete();
            DB::table('menu')->where('parent_id', $menuId)->delete();

            // Ensure Email Plugin exists under Setting (parent_id 2)
            DB::table('menu')->updateOrInsert(
                ['parent_id' => 2, 'routes' => 'emails/settings'],
                [
                    'menu_name' => 'Email Plugin',
                    'sort_order' => 6,
                    'show_menu' => 'Y',
                    'icon_class' => 'fa fa-gear',
                    'updated_at' => now()
                ]
            );

            DB::table('submenus')->updateOrInsert(
                ['menus_id' => 2, 'routes' => 'emails/settings'],
                [
                    'sub_menu_name' => 'Email Plugin',
                    'sort_order' => 6,
                    'show' => 'Y',
                    'updated_at' => now()
                ]
            );

            // Fetch Setting -> Email Plugin submenu ID
            $settingEmailSub = DB::table('submenus')->where('menus_id', 2)->where('routes', 'emails/settings')->first();
            $settingEmailMenu = DB::table('menu')->where('parent_id', 2)->where('routes', 'emails/settings')->first();

            // Update permissions for roles that have access to Setting or Emails
            $permissions = DB::table('permission')->get();
            foreach ($permissions as $permission) {
                $menuIds = json_decode($permission->menu_id, true) ?? [];
                $submenuIds = json_decode($permission->submenu_id, true) ?? [];

                $hasAccess = (int) $permission->role_id === 1
                    || in_array((string) $menuId, array_map('strval', $menuIds), true)
                    || in_array("2", array_map('strval', $menuIds), true);

                if ($hasAccess) {
                    if (!in_array((string) $menuId, array_map('strval', $menuIds))) {
                        $menuIds[] = (string) $menuId;
                    }
                    if ($settingEmailMenu && !in_array((string) $settingEmailMenu->id, array_map('strval', $menuIds))) {
                        $menuIds[] = (string) $settingEmailMenu->id;
                    }
                    if ($settingEmailSub && !in_array((string) $settingEmailSub->id, array_map('strval', $submenuIds))) {
                        $submenuIds[] = (string) $settingEmailSub->id;
                    }

                    DB::table('permission')->where('id', $permission->id)->update([
                        'menu_id' => json_encode(array_values(array_unique(array_map('strval', $menuIds)))),
                        'submenu_id' => json_encode(array_values(array_unique(array_map('strval', $submenuIds)))),
                        'updated_at' => now(),
                    ]);
                }
            }

            \Illuminate\Support\Facades\Cache::forget('global_portal_menus_tree');
            \Illuminate\Support\Facades\Cache::forget('global_portal_permissions');
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Error syncing email menu: ' . $e->getMessage());
        }
    }
}
