<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(PermissionSeeder::class);

        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $staffRole = Role::firstOrCreate(['name' => 'staff']);
        $customerRole = Role::firstOrCreate(['name' => 'customer']);

        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Restaurant Admin',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ]
        );
        $admin->name = 'Restaurant Admin';
        $admin->password = bcrypt('password');
        $admin->email_verified_at = now();
        $admin->save();

        if (!$admin->hasRole($adminRole->name)) {
            $admin->assignRole($adminRole);
        }

        $staff = User::firstOrCreate(
            ['email' => 'staff@restaurant.local'],
            [
                'name' => 'Kitchen Staff',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ]
        );
        if (!$staff->hasRole($staffRole->name)) {
            $staff->assignRole($staffRole);
        }

        $customer = User::firstOrCreate(
            ['email' => 'customer@restaurant.local'],
            [
                'name' => 'Default Customer',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ]
        );
        if (!$customer->hasRole($customerRole->name)) {
            $customer->assignRole($customerRole);
        }

        $this->call(MenuSeeder::class);
    }
}
