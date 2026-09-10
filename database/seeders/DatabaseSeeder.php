<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // CMS
        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
            GroupMenuSeeder::class,
            MenuSeeder::class,
            PriviledgeSeeder::class,

            // Website Content
            WisataSeeder::class,
            BudayaSeeder::class,
            KulinerSeeder::class,
            UmkmSeeder::class,
            SejarahSeeder::class,
        ]);
    }
}