<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

#[OA\Tag(name: 'Auth', description: 'Authentication')]
class AuthController extends Controller
{
    public function __construct(private AuthService $authService) {}

    #[OA\Post(
        path: '/api/login',
        summary: 'Login and receive a Sanctum token',
        tags: ['Auth'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['email', 'password'],
                properties: [
                    new OA\Property(property: 'email',    type: 'string', example: 'admin@cms.test'),
                    new OA\Property(property: 'password', type: 'string', example: 'Option101#'),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'Login successful'),
            new OA\Response(response: 401, description: 'Invalid credentials'),
        ]
    )]
    public function login(LoginRequest $request): JsonResponse
    {
        try {
            $result = $this->authService->login($request->email, $request->password);

            if (! $result['success']) {
                return api_response(null, 'Invalid credentials', 401);
            }

            return api_response(['token' => $result['token'], 'user' => $result['user']], 'Login successful');
        } catch (\Throwable $e) {
            return api_response(null, $e->getMessage(), 500);
        }
    }

    #[OA\Post(
        path: '/api/logout',
        summary: 'Logout (revoke current token)',
        security: [['sanctum' => []]],
        tags: ['Auth'],
        responses: [new OA\Response(response: 200, description: 'Logged out')]
    )]
    public function logout(): JsonResponse
    {
        try {
            request()->user()->currentAccessToken()->delete();
            return api_response(null, 'Logged out');
        } catch (\Throwable $e) {
            return api_response(null, $e->getMessage(), 500);
        }
    }

    #[OA\Get(
        path: '/api/me',
        summary: 'Get authenticated user',
        security: [['sanctum' => []]],
        tags: ['Auth'],
        responses: [new OA\Response(response: 200, description: 'Current user')]
    )]
    public function me(): JsonResponse
    {
        try {
            return api_response($this->authService->formatUser(request()->user()));
        } catch (\Throwable $e) {
            return api_response(null, $e->getMessage(), 500);
        }
    }
}
