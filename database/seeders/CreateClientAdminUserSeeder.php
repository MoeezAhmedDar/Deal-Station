<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class CreateClientAdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::create([
            'name' => 'Demo Account',
            'email' => 'demo@dealstation.com',
            'password' => bcrypt('Demo@123')
        ]);
        $role = Role::find(1);
        $user->assignRole([$role->id]);
    }
}
