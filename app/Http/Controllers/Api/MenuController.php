<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\MenuRequest;
use App\Http\Requests\MenuReorderRequest;
use App\Models\Menu;
use App\Services\MenuService;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

#[OA\Tag(name: 'Menus', description: 'Menu management')]
class MenuController extends Controller
{
    public function __construct(private MenuService $menuService) {}

    #[OA\Get(
        path: '/api/menus',
        summary: 'List all menus (nested tree)',
        security: [['sanctum' => []]],
        tags: ['Menus'],
        responses: [new OA\Response(response: 200, description: 'Menu tree')]
    )]
    public function index(): JsonResponse
    {
        try {
            return api_response($this->menuService->tree());
        } catch (\Throwable $e) {
            return api_response(null, $e->getMessage(), 500);
        }
    }

    #[OA\Post(
        path: '/api/menus',
        summary: 'Create a menu item',
        security: [['sanctum' => []]],
        tags: ['Menus'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['name'],
                properties: [
                    new OA\Property(property: 'name',       type: 'string',  description: 'Menu name (English)'),
                    new OA\Property(property: 'name_ar',    type: 'string',  description: 'Menu name (Arabic) — optional'),
                    new OA\Property(property: 'parent_id',  type: 'integer'),
                    new OA\Property(property: 'sort_order', type: 'integer'),
                ]
            )
        ),
        responses: [new OA\Response(response: 201, description: 'Menu created')]
    )]
    public function store(MenuRequest $request): JsonResponse
    {
        try {
            return api_response($this->menuService->create($request->validated()), 'Menu created', 201);
        } catch (\Throwable $e) {
            return api_response(null, $e->getMessage(), 500);
        }
    }

    #[OA\Put(
        path: '/api/menus/{id}',
        summary: 'Update a menu item',
        security: [['sanctum' => []]],
        tags: ['Menus'],
        parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))],
        requestBody: new OA\RequestBody(
            content: new OA\JsonContent(properties: [
                new OA\Property(property: 'name',       type: 'string',  description: 'Menu name (English)'),
                new OA\Property(property: 'name_ar',    type: 'string',  description: 'Menu name (Arabic) — optional'),
                new OA\Property(property: 'parent_id',  type: 'integer'),
                new OA\Property(property: 'sort_order', type: 'integer'),
            ])
        ),
        responses: [new OA\Response(response: 200, description: 'Menu updated')]
    )]
    public function update(MenuRequest $request, Menu $menu): JsonResponse
    {
        try {
            return api_response($this->menuService->update($menu, $request->validated()), 'Menu updated');
        } catch (\Throwable $e) {
            return api_response(null, $e->getMessage(), 500);
        }
    }

    #[OA\Delete(
        path: '/api/menus/{id}',
        summary: 'Delete a menu item',
        security: [['sanctum' => []]],
        tags: ['Menus'],
        parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))],
        responses: [new OA\Response(response: 200, description: 'Menu deleted')]
    )]
    public function destroy(Menu $menu): JsonResponse
    {
        try {
            $this->menuService->delete($menu);
            return api_response(null, 'Menu deleted');
        } catch (\Throwable $e) {
            return api_response(null, $e->getMessage(), 500);
        }
    }

    #[OA\Put(
        path: '/api/menus/reorder',
        summary: 'Reorder menus',
        security: [['sanctum' => []]],
        tags: ['Menus'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(properties: [
                new OA\Property(property: 'items', type: 'array', items: new OA\Items(
                    properties: [
                        new OA\Property(property: 'id',         type: 'integer'),
                        new OA\Property(property: 'sort_order', type: 'integer'),
                        new OA\Property(property: 'parent_id',  type: 'integer'),
                    ]
                )),
            ])
        ),
        responses: [new OA\Response(response: 200, description: 'Menus reordered')]
    )]
    public function reorder(MenuReorderRequest $request): JsonResponse
    {
        try {
            $this->menuService->reorder($request->validated()['items']);
            return api_response(null, 'Menus reordered');
        } catch (\Throwable $e) {
            return api_response(null, $e->getMessage(), 500);
        }
    }
}
