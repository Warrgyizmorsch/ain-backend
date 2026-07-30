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
    protected $description = 'Update menu permissions: Remove Prefix/Dynamic Pages/Subject Pages from Writer Admin (Role 8) and ensure Wallet is restricted to Admin (Role 1)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Updating menu permissions...');

        // 1. Remove Prefix (46), Dynamic Pages (47), Subject Pages (48) from Writer Admin (role_id 8)
        $writerAdminPerm = DB::table('permission')->where('role_id', 8)->first();
        if ($writerAdminPerm) {
            $menuIds = json_decode($writerAdminPerm->menu_id, true) ?? [];
            $updatedMenuIds = array_values(array_filter($menuIds, function ($id) {
                return !in_array((string) $id, ['46', '47', '48'], true);
            }));

            DB::table('permission')
                ->where('role_id', 8)
                ->update(['menu_id' => json_encode($updatedMenuIds)]);

            $this->info('Removed Prefix (46), Dynamic Pages (47), Subject Pages (48) from Writer Admin (Role 8).');
        }

        // 2. Ensure Wallet (36) is removed from all non-admin roles (role_id != 1)
        $nonAdminPerms = DB::table('permission')->where('role_id', '!=', 1)->get();
        $walletRemovedCount = 0;

        foreach ($nonAdminPerms as $perm) {
            $menuIds = json_decode($perm->menu_id, true) ?? [];
            if (in_array('36', $menuIds, true) || in_array(36, $menuIds, true)) {
                $updatedMenuIds = array_values(array_filter($menuIds, function ($id) {
                    return (string) $id !== '36';
                }));

                DB::table('permission')
                    ->where('id', $perm->id)
                    ->update(['menu_id' => json_encode($updatedMenuIds)]);

                $walletRemovedCount++;
            }
        }

        $this->info("Ensured Wallet menu (36) is restricted to Admin only (removed from {$walletRemovedCount} non-admin roles).");

        // 3. Ensure Super Admin (role_id 1) retains Wallet (36), Prefix (46), Dynamic Pages (47), Subject Pages (48)
        $adminPerm = DB::table('permission')->where('role_id', 1)->first();
        if ($adminPerm) {
            $menuIds = json_decode($adminPerm->menu_id, true) ?? [];
            $requiredAdminMenus = ['36', '46', '47', '48'];
            $modified = false;

            foreach ($requiredAdminMenus as $reqId) {
                if (!in_array($reqId, $menuIds, true) && !in_array((int) $reqId, $menuIds, true)) {
                    $menuIds[] = $reqId;
                    $modified = true;
                }
            }

            if ($modified) {
                DB::table('permission')
                    ->where('role_id', 1)
                    ->update(['menu_id' => json_encode(array_values($menuIds))]);
                $this->info('Updated Super Admin (Role 1) permissions to retain all admin menus.');
            }
        }

        $this->info('Menu permissions updated successfully!');

        return Command::SUCCESS;
    }
}
