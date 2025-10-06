<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = \App\Enums\PermissionEnums::cases();
        $permissions = array_map(fn($p) => $p->value . "_create", $permissions);
        $permissions = array_merge($permissions, array_map(fn($p) => $p->value . "_edit", \App\Enums\PermissionEnums::cases()));
        $permissions = array_merge($permissions, array_map(fn($p) => $p->value . "_read", \App\Enums\PermissionEnums::cases()));
        $permissions = array_merge($permissions, array_map(fn($p) => $p->value . "_delete", \App\Enums\PermissionEnums::cases()));

        Permission::query()->delete();

        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'admin'
            ]);
        }

        $superAdminRole = Role::where('name', 'super_admin')->first();
        if ($superAdminRole && class_exists(\App\Enums\PermissionEnums::class)) {
            foreach (\App\Enums\PermissionEnums::cases() as $permissionEnum) {
                $superAdminRole->givePermissionTo($permissionEnum->permissions());
            }
        }
    }

}
