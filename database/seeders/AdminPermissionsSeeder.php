<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class AdminPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
            'admin-merchant-list',
            'admin-merchant-create',
            'admin-merchant-edit',
            'admin-merchant-delete',

            'admin-branch-list',
            'admin-branch-create',
            'admin-branch-edit',
            'admin-branch-delete',

            // Live Starts Here

            'admin-city-list',
            'admin-city-create',
            'admin-city-edit',
            'admin-city-delete',

            'admin-category-list',
            'admin-category-create',
            'admin-category-edit',
            'admin-category-delete',

            'admin-plans-list',
            'admin-plans-create',
            'admin-plans-edit',
            'admin-plans-delete',

            'admin-subscription-list',
            'admin-subscription-create',
            'admin-subscription-edit',
            'admin-subscription-delete',

            'admin-offer-list',
            'admin-offer-create',
            'admin-offer-edit',
            'admin-offer-delete',

            'admin-member-list',
            'admin-member-create',
            'admin-member-edit',
            'admin-member-delete',

            'admin-campaign-list',
            'admin-campaign-create',
            'admin-campaign-edit',
            'admin-campaign-delete',

            'admin-advertisement-list',
            'admin-advertisement-create',
            'admin-advertisement-edit',
            'admin-advertisement-delete',

            'admin-report-list',
            'admin-report-create',
            'admin-report-edit',
            'admin-report-delete',
        ];
        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }
        $role = Role::find(1);
        $role->givePermissionTo($permissions);
    }
}
