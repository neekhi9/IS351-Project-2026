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
        // Seed permissions and roles
        $this->call(PermissionSeeder::class);

        // Create a default test user and assign admin for initial access
        $user = User::firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'password' => bcrypt('password'), // placeholder, OTP login preferred
            ]
        );

        // Ensure role exists and assign
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        if (!$user->hasRole($adminRole->name)) {
            $user->assignRole($adminRole);
        }
    }
}
