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
        $permissions = [
            // Role/User management
            'role-list',
            'role-create',
            'role-edit',
            'role-delete',
            'user-list',
            'user-create',
            'user-edit',
            'user-delete',

            // Restaurant menu management
            'menu-list',
            'menu-create',
            'menu-edit',
            'menu-delete',

            // Orders
            'order-create',
            'order-list-own',
            'order-view-own',
            'order-list-all',
            'order-update-status',

            // Reservations
            'reservation-create',
            'reservation-list-own',
            'reservation-list-all',
            'reservation-update-status',

            // Kitchen/staff
            'kitchen-view',
            'kitchen-update-order',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $staffRole = Role::firstOrCreate(['name' => 'staff']);
        $customerRole = Role::firstOrCreate(['name' => 'customer']);

        $adminRole->syncPermissions(Permission::all());

        $staffRole->syncPermissions([
            'menu-list',
            'order-list-all',
            'order-update-status',
            'reservation-list-all',
            'reservation-update-status',
            'kitchen-view',
            'kitchen-update-order',
        ]);

        $customerRole->syncPermissions([
            'menu-list',
            'order-create',
            'order-list-own',
            'order-view-own',
            'reservation-create',
            'reservation-list-own',
        ]);
    }
}
