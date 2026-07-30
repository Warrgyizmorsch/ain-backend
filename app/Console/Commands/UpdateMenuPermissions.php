<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class UpdateMenuPermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'permissions:update-menu-access';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update menu permissions: Configure direct Prefix/Dynamic/Subject Pages for SEO (Role 10) and under Other menu for Admin (Role 1), while excluding from Writer Admin (Role 8)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Updating menu permissions...');

        // 1. Set parent_id = 44 (Other) and show_menu = 'Y' for Wallet (36), Prefix (46), Dynamic Pages (47), Subject Pages (48)
        DB::table('menu')->whereIn('id', [36, 46, 47, 48])->update(['parent_id' => 44, 'show_menu' => 'Y']);

        // 2. Ensure Submenus for Wallet, Prefix, Dynamic Pages, Subject Pages exist under menu_id = 44 (Other)
        $submenus = [
            ['sub_menu_name' => 'Wallet', 'menus_id' => 44, 'routes' => 'admin/wallet/bulk-credit', 'sort_order' => 1, 'show' => 'Y'],
            ['sub_menu_name' => 'Prefix', 'menus_id' => 44, 'routes' => 'subjects', 'sort_order' => 2, 'show' => 'Y'],
            ['sub_menu_name' => 'Dynamic Pages', 'menus_id' => 44, 'routes' => 'service-pages', 'sort_order' => 3, 'show' => 'Y'],
            ['sub_menu_name' => 'Subject Pages', 'menus_id' => 44, 'routes' => 'subject-pages', 'sort_order' => 4, 'show' => 'Y'],
        ];

        $otherSubmenuIds = [];
        foreach ($submenus as $sub) {
            $existing = DB::table('submenus')
                ->where('menus_id', 44)
                ->where('routes', $sub['routes'])
                ->first();

            if (!$existing) {
                $insertedId = DB::table('submenus')->insertGetId($sub);
                $otherSubmenuIds[] = (string) $insertedId;
            } else {
                DB::table('submenus')->where('id', $existing->id)->update(['show' => 'Y']);
                $otherSubmenuIds[] = (string) $existing->id;
            }
        }

        // 3. Admin (Role 1): Should have menu_id 44 (Other) + all its submenus (Wallet, Prefix, Dynamic Pages, Subject Pages)
        // Admin should NOT have standalone menu_ids 46, 47, 48
        $adminPerm = DB::table('permission')->where('role_id', 1)->first();
        if ($adminPerm) {
            $menuIds = json_decode($adminPerm->menu_id, true) ?? [];
            $submenuIds = json_decode($adminPerm->submenu_id, true) ?? [];

            // Add 44, remove standalone 46, 47, 48
            if (!in_array('44', $menuIds, true) && !in_array(44, $menuIds, true)) {
                $menuIds[] = '44';
            }
            $menuIds = array_values(array_filter($menuIds, function ($id) {
                return !in_array((string) $id, ['46', '47', '48'], true);
            }));

            // Add other submenus
            foreach ($otherSubmenuIds as $subId) {
                if (!in_array($subId, $submenuIds, true) && !in_array((int) $subId, $submenuIds, true)) {
                    $submenuIds[] = $subId;
                }
            }

            DB::table('permission')
                ->where('role_id', 1)
                ->update([
                    'menu_id' => json_encode(array_values(array_unique($menuIds))),
                    'submenu_id' => json_encode(array_values(array_unique($submenuIds))),
                ]);

            $this->info('Updated Super Admin (Role 1) permissions.');
        }

        // 4. SEO (Role 10): Should have standalone menu_ids 46, 47, 48 directly. Should NOT have menu_id 44 (Other).
        $seoPerm = DB::table('permission')->where('role_id', 10)->first();
        if ($seoPerm) {
            $menuIds = json_decode($seoPerm->menu_id, true) ?? [];

            // Remove 44 (Other)
            $menuIds = array_values(array_filter($menuIds, function ($id) {
                return (string) $id !== '44';
            }));

            // Ensure 46, 47, 48 are present
            foreach (['46', '47', '48'] as $reqId) {
                if (!in_array($reqId, $menuIds, true) && !in_array((int) $reqId, $menuIds, true)) {
                    $menuIds[] = $reqId;
                }
            }

            DB::table('permission')
                ->where('role_id', 10)
                ->update(['menu_id' => json_encode(array_values(array_unique($menuIds)))]);

            $this->info('Updated SEO (Role 10) permissions.');
        }

        // 5. Writer Admin (Role 8): Should NOT have menu_ids 44, 46, 47, 48
        $writerAdminPerm = DB::table('permission')->where('role_id', 8)->first();
        if ($writerAdminPerm) {
            $menuIds = json_decode($writerAdminPerm->menu_id, true) ?? [];
            $updatedMenuIds = array_values(array_filter($menuIds, function ($id) {
                return !in_array((string) $id, ['44', '46', '47', '48'], true);
            }));

            DB::table('permission')
                ->where('role_id', 8)
                ->update(['menu_id' => json_encode($updatedMenuIds)]);

            $this->info('Removed Prefix, Dynamic Pages, Subject Pages from Writer Admin (Role 8).');
        }

        $this->info('Menu permissions updated successfully!');

        return Command::SUCCESS;
    }
}
