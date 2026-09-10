<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Menu;
use App\Models\GroupMenu;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        $configuration = GroupMenu::where('name', 'Configuration')->first();
        $master = GroupMenu::where('name', 'Master')->first();
        $user = GroupMenu::where('name', 'User')->first();
        $content = GroupMenu::where('name', 'Content')->first();

        if (!$configuration || !$master || !$user || !$content) {
            $this->command->error('Group menu belum tersedia. Jalankan GroupMenuSeeder terlebih dahulu.');
            return;
        }

        // =========================
        // CONFIGURATION
        // =========================

        Menu::updateOrCreate(
            ['name' => 'Manage Menu'],
            [
                'group_menu_id' => $configuration->id,
                'url' => '/menu',
                'sequence' => 1,
                'icon' => 'menu',
            ]
        );

        Menu::updateOrCreate(
            ['name' => 'Manage Group-Menu'],
            [
                'group_menu_id' => $configuration->id,
                'url' => '/group-menu',
                'sequence' => 2,
                'icon' => 'layers',
            ]
        );

        // =========================
        // MASTER
        // =========================

        Menu::updateOrCreate(
            ['name' => 'Manage Role'],
            [
                'group_menu_id' => $master->id,
                'url' => '/admin/roles',
                'sequence' => 1,
                'icon' => 'shield',
            ]
        );

        // =========================
        // USER
        // =========================

        Menu::updateOrCreate(
            ['name' => 'Manage User'],
            [
                'group_menu_id' => $user->id,
                'url' => '/admin/users',
                'sequence' => 1,
                'icon' => 'users',
            ]
        );

        // =========================
        // CONTENT
        // =========================

        Menu::updateOrCreate(
            ['name' => 'Manage Wisata'],
            [
                'group_menu_id' => $content->id,
                'url' => '/admin/wisata',
                'sequence' => 1,
                'icon' => 'map',
            ]
        );

        Menu::updateOrCreate(
            ['name' => 'Manage Budaya'],
            [
                'group_menu_id' => $content->id,
                'url' => '/admin/budaya',
                'sequence' => 2,
                'icon' => 'landmark',
            ]
        );

        Menu::updateOrCreate(
            ['name' => 'Manage Kuliner'],
            [
                'group_menu_id' => $content->id,
                'url' => '/admin/kuliner',
                'sequence' => 3,
                'icon' => 'utensils',
            ]
        );

        Menu::updateOrCreate(
            ['name' => 'Manage UMKM'],
            [
                'group_menu_id' => $content->id,
                'url' => '/admin/umkm',
                'sequence' => 4,
                'icon' => 'store',
            ]
        );

        Menu::updateOrCreate(
            ['name' => 'Manage Sejarah'],
            [
                'group_menu_id' => $content->id,
                'url' => '/admin/sejarah',
                'sequence' => 5,
                'icon' => 'book-open',
            ]
        );

        $this->command->info('Menu CMS berhasil dibuat/diperbarui.');
    }
}
