<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class LiveMenuPermissionSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(SeoMenuSeeder::class);
    }
}
