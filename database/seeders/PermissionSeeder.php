<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    private $permissions = [
        'branch-list',
        'branch-create',
        'branch-edit',
        'branch-delete',
        'role-list',
        'role-create',
        'role-edit',
        'role-delete',
        'user-list',
        'user-create',
        'user-edit',
        'user-delete',
    ];
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach ($this->permissions as $permission) {
            Permission::create(['name' => $permission]);
        }
    }
}
