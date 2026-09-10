<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\GroupMenu;

class GroupMenuSeeder extends Seeder
{
    public function run(): void
    {
        GroupMenu::updateOrCreate(
            ['name' => 'Configuration'],
            [
                'icon' => 'settings',
                'sequence' => 1,
            ]
        );

        GroupMenu::updateOrCreate(
            ['name' => 'Master'],
            [
                'icon' => 'database',
                'sequence' => 2,
            ]
        );

        GroupMenu::updateOrCreate(
            ['name' => 'User'],
            [
                'icon' => 'users',
                'sequence' => 3,
            ]
        );

        GroupMenu::updateOrCreate(
            ['name' => 'Content'],
            [
                'icon' => 'file-text',
                'sequence' => 4,
            ]
        );
    }
}
