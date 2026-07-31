<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WalletMenuSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('menu')->updateOrInsert(
            ['id' => 36],
            [
                'parent_id' => 44,
                'menu_name' => 'Wallet',
                'icon_class' => 'fa fa-wallet',
                'show_menu' => 'Y',
                'routes' => 'admin/wallet/bulk-credit',
                'sort_order' => 0,
                'updated_at' => now(),
                'created_at' => now(),
            ]
        );

        $permission = DB::table('permission')->where('role_id', 1)->first();
        $menuIds = $permission ? json_decode($permission->menu_id, true) : [];
        $submenuIds = $permission ? json_decode($permission->submenu_id, true) : [];

        $menuIds = is_array($menuIds) ? $menuIds : [];
        $submenuIds = is_array($submenuIds) ? $submenuIds : [];

        if (!in_array(36, array_map('intval', $menuIds), true)) {
            $menuIds[] = 36;
        }

        if (!in_array(44, array_map('intval', $menuIds), true)) {
            $menuIds[] = 44;
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
