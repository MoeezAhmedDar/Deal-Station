<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class MerchantPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
            'branch-list',
            'branch-create',
            'branch-edit',
            'branch-delete',

            'offer-list',
            'offer-create',
            'offer-edit',
            'offer-delete',

            'report-list',
            'report-create',
            'report-edit',
            'report-delete'
        ];
        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }
        $role = Role::where('name', '=', 'Merchant')->first();
        $role->givePermissionTo($permissions);
    }
}
