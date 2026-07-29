<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\MenuRequest;
use App\Http\Resources\MenuResource;
use App\Models\Menu;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

#[OA\Tag(name: 'Menus', description: 'Menu management')]
class MenuController extends Controller
{
    #[OA\Get(
        path: '/api/menus',
        summary: 'List all menus (nested tree)',
        security: [['sanctum' => []]],
        tags: ['Menus'],
        responses: [new OA\Response(response: 200, description: 'Menu tree')]
    )]
    public function index(): JsonResponse
    {
        $menus = Menu::with('children')->whereNull('parent_id')->orderBy('sort_order')->get();
        return api_response(MenuResource::collection($menus));
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
                    new OA\Property(property: 'name',       type: 'string'),
                    new OA\Property(property: 'parent_id',  type: 'integer'),
                    new OA\Property(property: 'sort_order', type: 'integer'),
                ]
            )
        ),
        responses: [new OA\Response(response: 201, description: 'Menu created')]
    )]
    public function store(MenuRequest $request): JsonResponse
    {
        $menu = Menu::create($request->validated());
        return api_response(new MenuResource($menu), 'Menu created', 201);
    }

    #[OA\Put(
        path: '/api/menus/{id}',
        summary: 'Update a menu item',
        security: [['sanctum' => []]],
        tags: ['Menus'],
        parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))],
        requestBody: new OA\RequestBody(
            content: new OA\JsonContent(properties: [
                new OA\Property(property: 'name',       type: 'string'),
                new OA\Property(property: 'parent_id',  type: 'integer'),
                new OA\Property(property: 'sort_order', type: 'integer'),
            ])
        ),
        responses: [new OA\Response(response: 200, description: 'Menu updated')]
    )]
    public function update(MenuRequest $request, Menu $menu): JsonResponse
    {
        $menu->update($request->validated());
        return api_response(new MenuResource($menu), 'Menu updated');
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
        $menu->delete();
        return api_response(null, 'Menu deleted');
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
    public function reorder(Request $request): JsonResponse
    {
        $request->validate([
            'items'              => ['required', 'array'],
            'items.*.id'         => ['required', 'exists:menus,id'],
            'items.*.sort_order' => ['required', 'integer'],
            'items.*.parent_id'  => ['nullable', 'exists:menus,id'],
        ]);

        foreach ($request->items as $item) {
            Menu::where('id', $item['id'])->update([
                'sort_order' => $item['sort_order'],
                'parent_id'  => $item['parent_id'] ?? null,
            ]);
        }

        return api_response(null, 'Menus reordered');
    }
}
