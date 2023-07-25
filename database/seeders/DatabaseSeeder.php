<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $user = User::factory()->create([
            'first_name' => 'User',
            'last_name' => 'Test',
            'email' => 'test@example.com'
        ]);

        $admin = User::factory()->create([
            'first_name' => 'Admin',
            'last_name' => 'Test',
            'email' => 'admin@example.com'
        ]);

        $permission_admin = Permission::factory()->create([
            'slug' => 'admin',
            'name' => 'Admin'
        ]);

        $permission_user = Permission::factory()->create([
            'slug' => 'view-exams',
            'name' => 'View Exams'
        ]);

        $role_admin = Role::factory()->create([
            'slug' => 'admin',
            'name' => 'Admin'
        ]);

        $role_user = Role::factory()->create([
            'slug' => 'user',
            'name' => 'User'
        ]);

        $role_user->permissions()->attach($permission_user);
        $role_admin->permissions()->attach($permission_admin);

        $user->permissions()->attach($permission_user);
        $user->roles()->attach($role_user);

        $admin->permissions()->attach($permission_admin);
        $admin->roles()->attach($role_admin);
    }
}
