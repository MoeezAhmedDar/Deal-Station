<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */


    public function run()
    {
        $permissions = [
            'admin-role-list',
            'admin-role-create',
            'admin-role-edit',
            'admin-role-delete',

            'admin-user-list',
            'admin-user-create',
            'admin-user-edit',
            'admin-user-delete'
        ];
        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }
        // $role = Role::find(1);
        // $permissions = Permission::pluck('id', 'id')->all();
        // $role->syncPermissions($permissions);
    }
}
