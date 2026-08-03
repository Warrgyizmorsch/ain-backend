<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class LiveMenuSetupSeeder extends Seeder
{
    public function run(): void
    {
        $snapshot = json_decode(
            file_get_contents(database_path('seeders/data/live-menu-snapshot.json')),
            true,
            512,
            JSON_THROW_ON_ERROR
        );
        $now = now();

        Schema::disableForeignKeyConstraints();

        try {
            foreach ($snapshot['roles'] as $role) {
                DB::table('roles')->updateOrInsert(['id' => $role['id']], [
                    'role' => $role['role'],
                    'flag' => $role['flag'],
                ]);
            }

            foreach ($snapshot['menus'] as $menu) {
                $this->upsertMenu($menu, $now);
            }

            foreach ($snapshot['submenus'] as $submenu) {
                DB::table('submenus')->updateOrInsert(['id' => $submenu['id']], array_merge($submenu, [
                    'created_at' => $now,
                    'updated_at' => $now,
                ]));
            }

            foreach ($snapshot['permissions'] as $permission) {
                DB::table('permission')->updateOrInsert(['role_id' => $permission['role_id']], [
                    'menu_id' => $permission['menu_id'],
                    'submenu_id' => $permission['submenu_id'],
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        } finally {
            Schema::enableForeignKeyConstraints();
        }
    }

    private function upsertMenu(array $menu, $now): void
    {
        DB::table('menu')->updateOrInsert(['id' => $menu['id']], array_merge($menu, [
            'created_at' => $now,
            'updated_at' => $now,
        ]));
    }
}
