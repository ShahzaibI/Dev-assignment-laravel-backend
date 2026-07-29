<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\MenuResource;
use App\Http\Resources\PageResource;
use App\Services\PublicService;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

#[OA\Tag(name: 'Public', description: 'Public endpoints (no auth required)')]
class PublicController extends Controller
{
    public function __construct(private PublicService $publicService) {}

    #[OA\Get(
        path: '/api/public/menus',
        summary: 'Get public menu tree with published pages',
        tags: ['Public'],
        responses: [new OA\Response(response: 200, description: 'Menu tree')]
    )]
    public function menus(): JsonResponse
    {
        try {
            return api_response(MenuResource::collection($this->publicService->menuTree()));
        } catch (\Throwable $e) {
            return api_response(null, $e->getMessage(), 500);
        }
    }

    #[OA\Get(
        path: '/api/public/pages/{slug}',
        summary: 'Get a single published page by slug',
        tags: ['Public'],
        parameters: [new OA\Parameter(name: 'slug', in: 'path', required: true, schema: new OA\Schema(type: 'string'))],
        responses: [
            new OA\Response(response: 200, description: 'Page detail'),
            new OA\Response(response: 404, description: 'Not found'),
        ]
    )]
    public function page(string $slug): JsonResponse
    {
        try {
            return api_response(new PageResource($this->publicService->publishedPage($slug)));
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException) {
            return api_response(null, 'Page not found', 404);
        } catch (\Throwable $e) {
            return api_response(null, $e->getMessage(), 500);
        }
    }
}
