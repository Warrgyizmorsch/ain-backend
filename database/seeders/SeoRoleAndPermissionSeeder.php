<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class SeoRoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();

        // 1. Ensure the SEO role exists (or insert/update it)
        $role = array (
  'id' => 10,
  'role' => 'SEO
',
  'flag' => 0,
  'updated_at' => '2026-04-14 05:42:00',
);
        
        if (!empty($role)) {
            DB::table('roles')->updateOrInsert(
                ['id' => $role['id']],
                [
                    'role' => $role['role'],
                    'flag' => $role['flag'],
                    'created_at' => $role['created_at'] ?? null,
                    'updated_at' => $role['updated_at'] ?? null,
                ]
            );
        }

        // 2. Insert or update the permissions for SEO role (role_id = 10)
        $permissions = array (
  0 => 
  array (
    'id' => 16,
    'role_id' => 10,
    'menu_id' => '["26","1","11","46","47","48","44","15","16","27","28","30","38","39"]',
    'submenu_id' => '["26","27","28","29","19","20","21","22","30","31","32","33","34","35","45","49","50","46","47","48"]',
    'updated_at' => '2026-07-16 10:37:27',
    'created_at' => '2024-07-30 16:19:55',
  ),
);

        foreach ($permissions as $perm) {
            DB::table('permission')->updateOrInsert(
                ['role_id' => $perm['role_id']],
                [
                    'menu_id' => $perm['menu_id'],
                    'submenu_id' => $perm['submenu_id'],
                    'created_at' => $perm['created_at'] ?? null,
                    'updated_at' => $perm['updated_at'] ?? null,
                ]
            );
        }

        Schema::enableForeignKeyConstraints();
    }
}