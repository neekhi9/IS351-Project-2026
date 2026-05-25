<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Define base permissions and registration workflow permissions
        $permissions = [
            // Role management
            'role-list',
            'role-create',
            'role-edit',
            'role-delete',

            // User management
            'user-list',
            'user-create',
            'user-edit',
            'user-delete',

            // Registration review workflow
            'registration-list',
            'registration-review',
            'registration-approve',
            'registration-decline',
            'registration-mark-invalid',
        ];

        // Create permissions idempotently
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create roles idempotently
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $userRole  = Role::firstOrCreate(['name' => 'user']);

        // Give admin all permissions
        $adminRole->syncPermissions(Permission::all());

        // Keep 'user' role minimal by default (no management permissions)
        // You can assign specific front-facing permissions later if needed.
    }
}
