<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::create([
            'name' => 'Super Admin',
            'email' => 'admin@gym.com',
            'password' => Hash::make('admin')
        ]);

        $branch = Branch::where('name', 'Main Branch')->first();
        $user->branches()->sync($branch->id);

        $role = Role::where('name', 'Admin')->first();
        $user->assignRole([$role->id]);
    }
}
