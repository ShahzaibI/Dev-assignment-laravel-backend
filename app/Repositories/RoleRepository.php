<?php

namespace App\Repositories;

use Illuminate\Support\Collection;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleRepository
{
    public function all(): Collection
    {
        return Role::with('permissions')->get();
    }

    public function allPermissions(): Collection
    {
        return Permission::all()->pluck('name');
    }

    public function create(string $name): Role
    {
        return Role::create(['name' => $name, 'guard_name' => 'sanctum']);
    }

    public function update(Role $role, string $name): Role
    {
        $role->update(['name' => $name]);
        return $role;
    }

    public function syncPermissions(Role $role, array $permissions): Role
    {
        $role->syncPermissions($permissions);
        return $role->load('permissions');
    }

    public function delete(Role $role): void
    {
        $role->delete();
    }
}
