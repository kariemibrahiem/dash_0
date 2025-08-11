<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            'create_user',
            'update_user',
            'read_user',
            'delete_user',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        $roles = ['super_admin', 'admin', 'editor', 'viewer'];

        foreach ($roles as $roleName) {
            Role::firstOrCreate(['name' => $roleName]);
        }

        $superAdminRole = Role::where('name', 'super_admin')->first();
        if ($superAdminRole) {
            $superAdminRole->givePermissionTo($permissions);

            if (class_exists(\App\Enums\PermissionEnums::class)) {
                $permissionEnums = \App\Enums\PermissionEnums::cases();
                foreach ($permissionEnums as $permissionEnum) {
                    $perms = $permissionEnum->permissions();
                    if (is_array($perms)) {
                        $superAdminRole->givePermissionTo($perms);
                    } else {
                        $superAdminRole->givePermissionTo($perms);
                    }
                }
            }
        }

        $adminRole = Role::where('name', 'admin')->first();
        $editorRole = Role::where('name', 'editor')->first();
        $viewerRole = Role::where('name', 'viewer')->first();

        if ($adminRole) {
            $adminRole->givePermissionTo(['read_user', 'update_user']);
            if (class_exists(\App\Enums\PermissionEnums::class)) {
                $adminRole->givePermissionTo(\App\Enums\PermissionEnums::cases()[0]->permissions());
            }
        }

        if ($editorRole) {
            $editorRole->givePermissionTo(['read_user']);
            if (class_exists(\App\Enums\PermissionEnums::class)) {
                $editorRole->givePermissionTo(\App\Enums\PermissionEnums::cases()[1]->permissions());
            }
        }

        if ($viewerRole) {
            $viewerRole->givePermissionTo(['read_user']);
            if (class_exists(\App\Enums\PermissionEnums::class)) {
                $viewerRole->givePermissionTo(\App\Enums\PermissionEnums::cases()[2]->permissions());
            }
        }
    }
}
