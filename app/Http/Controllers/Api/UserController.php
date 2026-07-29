<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use OpenApi\Attributes as OA;

#[OA\Tag(name: 'Users', description: 'User management')]
class UserController extends Controller
{
    #[OA\Get(
        path: '/api/users',
        summary: 'List all users',
        security: [['sanctum' => []]],
        tags: ['Users'],
        responses: [new OA\Response(response: 200, description: 'Users list')]
    )]
    public function index(): JsonResponse
    {
        return api_response(UserResource::collection(User::all()));
    }

    #[OA\Post(
        path: '/api/users',
        summary: 'Create a user',
        security: [['sanctum' => []]],
        tags: ['Users'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['name', 'email', 'password', 'role'],
                properties: [
                    new OA\Property(property: 'name',     type: 'string'),
                    new OA\Property(property: 'email',    type: 'string'),
                    new OA\Property(property: 'password', type: 'string'),
                    new OA\Property(property: 'role',     type: 'string', example: 'moderator'),
                ]
            )
        ),
        responses: [new OA\Response(response: 201, description: 'User created')]
    )]
    public function store(UserRequest $request): JsonResponse
    {
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);
        $user->assignRole($request->role);

        return api_response(new UserResource($user), 'User created', 201);
    }

    #[OA\Get(
        path: '/api/users/{id}',
        summary: 'Get a user',
        security: [['sanctum' => []]],
        tags: ['Users'],
        parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))],
        responses: [new OA\Response(response: 200, description: 'User detail')]
    )]
    public function show(User $user): JsonResponse
    {
        return api_response(new UserResource($user));
    }

    #[OA\Put(
        path: '/api/users/{id}',
        summary: 'Update a user',
        security: [['sanctum' => []]],
        tags: ['Users'],
        parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))],
        requestBody: new OA\RequestBody(
            content: new OA\JsonContent(properties: [
                new OA\Property(property: 'name',     type: 'string'),
                new OA\Property(property: 'email',    type: 'string'),
                new OA\Property(property: 'password', type: 'string'),
                new OA\Property(property: 'role',     type: 'string'),
            ])
        ),
        responses: [new OA\Response(response: 200, description: 'User updated')]
    )]
    public function update(UserRequest $request, User $user): JsonResponse
    {
        $user->update(array_filter([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => $request->password ? Hash::make($request->password) : null,
        ]));
        $user->syncRoles([$request->role]);

        return api_response(new UserResource($user), 'User updated');
    }

    #[OA\Delete(
        path: '/api/users/{id}',
        summary: 'Delete a user',
        security: [['sanctum' => []]],
        tags: ['Users'],
        parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))],
        responses: [new OA\Response(response: 200, description: 'User deleted')]
    )]
    public function destroy(User $user): JsonResponse
    {
        $user->delete();
        return api_response(null, 'User deleted');
    }
}
