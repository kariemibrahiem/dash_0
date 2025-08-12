<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Admin;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // First seed roles and permissions
        $this->call([
            RoleSeeder::class,
            PermissionSeeder::class,
        ]);

        // Then create admin user
        Admin::create([
            "user_name" => "admin",
            "email" => "admin@admin.com",
            "password" => bcrypt("admin"),
            "code" => "admin",
            "image" => "testImage"
        ]);

        // Now assign the role (roles exist now)
        Admin::latest()->first()->assignRole("super_admin");
    }

}
