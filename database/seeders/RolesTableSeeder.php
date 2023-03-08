<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RolesTableSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::create(['name' => 'Client']);
        $roles = [
            'Merchant',
            'Cashier',
            'Member',
        ];
        foreach ($roles as $role) {
            Role::create(['name' => $role]);
        }
    }
}
