<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            TrialPackageSeeder::class,
            PermissionTableSeeder::class,
            CreateAdminUserSeeder::class,
            CreateClientAdminUserSeeder::class,
            RolesTableSeeder::class,
            AdminPermissionsSeeder::class,
            MerchantPermissionsSeeder::class,
            SettingTableSeeder::class,
        ]);
    }
}
