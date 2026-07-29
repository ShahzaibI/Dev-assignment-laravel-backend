<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

#[OA\Tag(name: 'Users', description: 'User management')]
class UserController extends Controller
{
    public function __construct(private UserService $userService) {}

    #[OA\Get(
        path: '/api/users',
        summary: 'List all users',
        security: [['sanctum' => []]],
        tags: ['Users'],
        responses: [new OA\Response(response: 200, description: 'Users list')]
    )]
    public function index(): JsonResponse
    {
        try {
            return api_response($this->userService->all());
        } catch (\Throwable $e) {
            return api_response(null, $e->getMessage(), 500);
        }
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
        try {
            return api_response($this->userService->create($request->validated()), 'User created', 201);
        } catch (\Throwable $e) {
            return api_response(null, $e->getMessage(), 500);
        }
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
        try {
            return api_response($this->userService->show($user));
        } catch (\Throwable $e) {
            return api_response(null, $e->getMessage(), 500);
        }
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
        try {
            return api_response($this->userService->update($user, $request->validated()), 'User updated');
        } catch (\Throwable $e) {
            return api_response(null, $e->getMessage(), 500);
        }
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
        try {
            $this->userService->delete($user);
            return api_response(null, 'User deleted');
        } catch (\Throwable $e) {
            return api_response(null, $e->getMessage(), 500);
        }
    }
}
