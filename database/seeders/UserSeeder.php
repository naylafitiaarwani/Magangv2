<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->updateOrInsert(
            ['email' => 'superadmin@local.com'],
            [
                'name' => 'Superadmin',
                'password' => Hash::make('super123'),
                'image' => 'assets/image/default-user.png',
                'role_id' => 1,
                'updated_at' => now(),
                'created_at' => now(),
            ]
        );

        $this->command->info('Superadmin berhasil dibuat/diperbarui.');
    }
}
