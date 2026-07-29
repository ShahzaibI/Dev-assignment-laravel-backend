<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\RoleRequest;
use App\Http\Resources\RoleResource;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

#[OA\Tag(name: 'Roles', description: 'Role & permission management')]
class RoleController extends Controller
{
    #[OA\Get(
        path: '/api/roles',
        summary: 'List all roles with permissions',
        security: [['sanctum' => []]],
        tags: ['Roles'],
        responses: [new OA\Response(response: 200, description: 'Roles list')]
    )]
    public function index(): JsonResponse
    {
        return api_response(RoleResource::collection(Role::with('permissions')->get()));
    }

    #[OA\Get(
        path: '/api/permissions',
        summary: 'List all available permissions',
        security: [['sanctum' => []]],
        tags: ['Roles'],
        responses: [new OA\Response(response: 200, description: 'Permissions list')]
    )]
    public function permissions(): JsonResponse
    {
        return api_response(Permission::all()->pluck('name'));
    }

    #[OA\Post(
        path: '/api/roles',
        summary: 'Create a role',
        security: [['sanctum' => []]],
        tags: ['Roles'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['name'],
                properties: [
                    new OA\Property(property: 'name',        type: 'string'),
                    new OA\Property(property: 'permissions', type: 'array', items: new OA\Items(type: 'string')),
                ]
            )
        ),
        responses: [new OA\Response(response: 201, description: 'Role created')]
    )]
    public function store(RoleRequest $request): JsonResponse
    {
        $role = Role::create(['name' => $request->name, 'guard_name' => 'sanctum']);
        if ($request->permissions) {
            $role->syncPermissions($request->permissions);
        }
        return api_response(new RoleResource($role->load('permissions')), 'Role created', 201);
    }

    #[OA\Put(
        path: '/api/roles/{id}',
        summary: 'Update a role',
        security: [['sanctum' => []]],
        tags: ['Roles'],
        parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))],
        requestBody: new OA\RequestBody(
            content: new OA\JsonContent(properties: [
                new OA\Property(property: 'name',        type: 'string'),
                new OA\Property(property: 'permissions', type: 'array', items: new OA\Items(type: 'string')),
            ])
        ),
        responses: [new OA\Response(response: 200, description: 'Role updated')]
    )]
    public function update(RoleRequest $request, Role $role): JsonResponse
    {
        $role->update(['name' => $request->name]);
        $role->syncPermissions($request->permissions ?? []);
        return api_response(new RoleResource($role->load('permissions')), 'Role updated');
    }

    #[OA\Delete(
        path: '/api/roles/{id}',
        summary: 'Delete a role',
        security: [['sanctum' => []]],
        tags: ['Roles'],
        parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))],
        responses: [new OA\Response(response: 200, description: 'Role deleted')]
    )]
    public function destroy(Role $role): JsonResponse
    {
        $role->delete();
        return api_response(null, 'Role deleted');
    }
}
