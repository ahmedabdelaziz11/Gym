<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $role = Role::create(['name' => 'Admin']);

        $permissions = Permission::pluck('id', 'id')->all();
        $role->syncPermissions($permissions);

        Role::create(['name' => 'sales']);
    }
}
