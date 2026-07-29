<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\MenuResource;
use App\Http\Resources\PageResource;
use App\Models\Menu;
use App\Models\Page;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

#[OA\Tag(name: 'Public', description: 'Public endpoints (no auth required)')]
class PublicController extends Controller
{
    #[OA\Get(
        path: '/api/public/menus',
        summary: 'Get public menu tree with published pages',
        tags: ['Public'],
        responses: [new OA\Response(response: 200, description: 'Menu tree')]
    )]
    public function menus(): JsonResponse
    {
        $menus = Menu::with(['children', 'pages' => function ($q) {
            $q->published()->select('id', 'title', 'slug', 'menu_id', 'cover_image');
        }])->whereNull('parent_id')->orderBy('sort_order')->get();

        return api_response(MenuResource::collection($menus));
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
        $page = Page::with(['menu', 'creator'])
            ->published()
            ->where('slug', $slug)
            ->firstOrFail();

        return api_response(new PageResource($page));
    }
}
