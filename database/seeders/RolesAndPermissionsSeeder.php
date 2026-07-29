<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            'pages.list', 'pages.create', 'pages.edit', 'pages.delete', 'pages.restore',
            'menus.list', 'menus.create', 'menus.edit', 'menus.delete',
            'users.list', 'users.create', 'users.edit', 'users.delete',
            'roles.list', 'roles.create', 'roles.edit', 'roles.delete',
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm, 'guard_name' => 'sanctum']);
        }

        $admin = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'sanctum']);
        $admin->syncPermissions($permissions);

        $moderator = Role::firstOrCreate(['name' => 'moderator', 'guard_name' => 'sanctum']);
        $moderator->syncPermissions(['pages.list', 'pages.create', 'pages.edit', 'menus.list']);
    }
}
