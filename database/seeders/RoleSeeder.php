<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        Role::updateOrCreate(
            ['name' => 'Super Admin'],
            [
                'description' => 'Memiliki akses penuh terhadap seluruh fitur CMS.',
            ]
        );

        Role::updateOrCreate(
            ['name' => 'Admin'],
            [
                'description' => 'Administrator dengan akses terbatas sesuai privilege.',
            ]
        );
    }
}
