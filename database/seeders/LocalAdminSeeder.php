<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use RuntimeException;

class LocalAdminSeeder extends Seeder
{
    public function run(): void
    {
        if (!app()->environment('local')) {
            throw new RuntimeException('LocalAdminSeeder is disabled outside the local environment.');
        }

        User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Local Admin',
                'username' => 'local_admin',
                'password' => Hash::make('12345678'),
                'role_id' => 1,
                'status' => 1,
                'verifyed' => 1,
            ]
        );

        $this->command?->info('Local admin ready: admin@gmail.com');
    }
}
