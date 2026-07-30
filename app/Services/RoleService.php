<?php

namespace App\Services;

use App\Http\Resources\RoleResource;
use App\Repositories\RoleRepository;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Collection as SupportCollection;
use Spatie\Permission\Models\Role;

class RoleService
{
    public function __construct(private RoleRepository $roleRepo) {}

    public function all(): AnonymousResourceCollection
    {
        return RoleResource::collection($this->roleRepo->all());
    }

    public function allPermissions(): SupportCollection
    {
        return $this->roleRepo->allPermissions();
    }

    public function create(string $name, array $permissions = []): RoleResource
    {
        $role = $this->roleRepo->create($name);
        return new RoleResource($this->roleRepo->syncPermissions($role, $permissions));
    }

    public function update(Role $role, string $name, array $permissions = []): RoleResource
    {
        $role = $this->roleRepo->update($role, $name);

        // Check if permissions actually changed before revoking tokens
        $currentPerms = $role->permissions->pluck('name')->sort()->values()->toArray();
        $newPerms     = collect($permissions)->sort()->values()->toArray();

        $updated = $this->roleRepo->syncPermissions($role, $permissions);

        if ($currentPerms !== $newPerms) {
            $role->users()->each(fn($user) => $user->tokens()->delete());
        }

        return new RoleResource($updated);
    }

    public function delete(Role $role): void
    {
        if ($role->users()->exists()) {
            throw new \Exception('Cannot delete a role that is assigned to users.');
        }

        $this->roleRepo->delete($role);
    }
}
