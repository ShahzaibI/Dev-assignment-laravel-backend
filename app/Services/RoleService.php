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
        return new RoleResource($this->roleRepo->syncPermissions($role, $permissions));
    }

    public function delete(Role $role): void
    {
        $this->roleRepo->delete($role);
    }
}
