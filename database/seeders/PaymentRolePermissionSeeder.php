<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class PaymentRolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Command to run on server:
     * php artisan db:seed --class=PaymentRolePermissionSeeder
     */
    public function run(): void
    {
        $this->command->info('--- Starting Payment Role & Permission Seeder ---');

        // 1. Ensure Role 11 (Payment Role) exists in `roles` table
        $roleId = 11;
        $existingRole = DB::table('roles')->where('id', $roleId)->first();

        if (!$existingRole) {
            DB::table('roles')->insert([
                'id'         => $roleId,
                'role'       => 'Payment Role',
                'flag'       => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $this->command->info("Created Role ID {$roleId}: Payment Role");
        } else {
            DB::table('roles')->where('id', $roleId)->update([
                'flag'       => 0,
                'updated_at' => now(),
            ]);
            $this->command->info("Verified Role ID {$roleId}: {$existingRole->role}");
        }

        // 2. Identify or create the 'Payments' menu (parent_id null, routes: Payments)
        $paymentMenu = DB::table('menu')
            ->where(function ($q) {
                $q->where('routes', 'Payments')
                  ->orWhere('routes', 'payments')
                  ->orWhere('menu_name', 'payment')
                  ->orWhere('menu_name', 'Payments');
            })
            ->first();

        if (!$paymentMenu) {
            $paymentMenuId = DB::table('menu')->insertGetId([
                'id'         => 29,
                'parent_id'  => null,
                'menu_name'  => 'payment',
                'icon_class' => 'fa fa-money',
                'show_menu'  => 'Y',
                'routes'     => 'Payments',
                'sort_order' => 7,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $this->command->info("Created Menu 'payment' (ID: {$paymentMenuId}, Route: Payments)");
        } else {
            $paymentMenuId = $paymentMenu->id;
            $this->command->info("Found existing Payments Menu ID: {$paymentMenuId}");
        }

        // 3. Identify Dashboard menu
        $dashboardMenu = DB::table('menu')
            ->where('routes', 'dashboard')
            ->orWhere('id', 1)
            ->first();
        $dashboardMenuId = $dashboardMenu ? $dashboardMenu->id : 1;

        // 4. Identify any submenus related to Payments
        $paymentSubmenuIds = DB::table('submenus')
            ->where(function ($q) use ($paymentMenuId) {
                $q->where('menus_id', $paymentMenuId)
                  ->orWhere('routes', 'Payments')
                  ->orWhere('routes', 'payments')
                  ->orWhere('routes', 'revoke-payments')
                  ->orWhere('routes', 'my-revoke-payments');
            })
            ->pluck('id')
            ->toArray();

        // 5. Build menu_id and submenu_id arrays for Role 11
        $assignedMenuIds = array_values(array_unique([
            (string) $dashboardMenuId,
            (string) $paymentMenuId,
            '1',
            '29'
        ]));

        $assignedSubmenuIds = array_values(array_unique(
            array_merge(['10'], array_map('strval', $paymentSubmenuIds))
        ));

        // 6. Update or insert into `permission` table for Role 11
        $existingPerm = DB::table('permission')->where('role_id', $roleId)->first();

        if ($existingPerm) {
            $currentMenus = json_decode($existingPerm->menu_id, true) ?? [];
            $currentSubmenus = json_decode($existingPerm->submenu_id, true) ?? [];
            if (!is_array($currentMenus)) $currentMenus = [];
            if (!is_array($currentSubmenus)) $currentSubmenus = [];

            $finalMenuIds = array_values(array_unique(array_merge(
                array_map('strval', $currentMenus),
                $assignedMenuIds
            )));

            $finalSubmenuIds = array_values(array_unique(array_merge(
                array_map('strval', $currentSubmenus),
                $assignedSubmenuIds
            )));

            DB::table('permission')->where('role_id', $roleId)->update([
                'menu_id'    => json_encode($finalMenuIds),
                'submenu_id' => json_encode($finalSubmenuIds),
                'updated_at' => now(),
            ]);
            $this->command->info("Updated permission for Role {$roleId}. Menus: " . json_encode($finalMenuIds));
        } else {
            DB::table('permission')->insert([
                'role_id'    => $roleId,
                'menu_id'    => json_encode($assignedMenuIds),
                'submenu_id' => json_encode($assignedSubmenuIds),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $this->command->info("Inserted permission for Role {$roleId}. Menus: " . json_encode($assignedMenuIds));
        }

        // 7. Verify / Assign user `user101@warrgyizmorsch.com` to Role 11
        $targetEmail = 'user101@warrgyizmorsch.com';
        $user = DB::table('users')->where('email', $targetEmail)->first();

        if ($user) {
            DB::table('users')->where('id', $user->id)->update([
                'role_id'    => $roleId,
                'flag'       => 0,
                'updated_at' => now(),
            ]);
            $this->command->info("Assigned user {$targetEmail} (ID: {$user->id}) to Role {$roleId} (Payment Role)");

            // If user previously had a different role, also ensure Payments is added to that role's permissions
            if ((int) $user->role_id !== $roleId && (int) $user->role_id > 0) {
                $oldRoleId = (int) $user->role_id;
                $oldPerm = DB::table('permission')->where('role_id', $oldRoleId)->first();
                if ($oldPerm) {
                    $oldMenus = json_decode($oldPerm->menu_id, true) ?? [];
                    if (is_array($oldMenus) && !in_array((string) $paymentMenuId, array_map('strval', $oldMenus))) {
                        $oldMenus[] = (string) $paymentMenuId;
                        DB::table('permission')->where('role_id', $oldRoleId)->update([
                            'menu_id'    => json_encode(array_values(array_unique(array_map('strval', $oldMenus)))),
                            'updated_at' => now(),
                        ]);
                    }
                }
            }
        } else {
            $this->command->warn("User {$targetEmail} was not found in the users table yet. Role {$roleId} permissions are ready whenever the user is registered/synced.");
        }

        // 8. Clear permission cache
        Cache::forget('role-allowed-routes-11');
        if ($user && isset($user->role_id)) {
            Cache::forget('role-allowed-routes-' . $user->role_id);
        }
        try {
            Artisan::call('cache:clear');
            $this->command->info('Cleared application cache successfully.');
        } catch (\Throwable $e) {
            $this->command->warn('Cache clear note: ' . $e->getMessage());
        }

        $this->command->info('--- Payment Role & Permission Seeding Completed Successfully! ---');
    }
}
