<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Menu;
use App\Models\Priviledge;

class PriviledgeSeeder extends Seeder
{
    public function run(): void
    {
        $superAdmin = Role::where('name', 'Super Admin')->first();
        $admin = Role::where('name', 'Admin')->first();

        $menuNames = [
            'Manage Menu',
            'Manage Group-Menu',
            'Manage Role',
            'Manage User',
            'Manage Wisata',
            'Manage Budaya',
            'Manage Kuliner',
            'Manage UMKM',
            'Manage Sejarah',
        ];

        foreach ($menuNames as $menuName) {
            $menu = Menu::where('name', $menuName)->first();

            if (!$menu) {
                continue;
            }

            // Super Admin: full access
            if ($superAdmin) {
                Priviledge::updateOrCreate(
                    [
                        'role_id' => $superAdmin->id,
                        'menu_id' => $menu->id,
                    ],
                    [
                        'view' => 1,
                        'add' => 1,
                        'edit' => 1,
                        'delete' => 1,
                        'other' => 1,
                    ]
                );
            }

            // Admin: no access by default
            if ($admin) {
                Priviledge::updateOrCreate(
                    [
                        'role_id' => $admin->id,
                        'menu_id' => $menu->id,
                    ],
                    [
                        'view' => 0,
                        'add' => 0,
                        'edit' => 0,
                        'delete' => 0,
                        'other' => 0,
                    ]
                );
            }
        }
    }
}
