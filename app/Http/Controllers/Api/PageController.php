<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\PageRequest;
use App\Models\Page;
use App\Services\PageService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

#[OA\Tag(name: 'Pages', description: 'Page management')]
class PageController extends Controller
{
    public function __construct(private PageService $pageService) {}

    #[OA\Get(
        path: '/api/pages',
        summary: 'List pages (paginated, searchable, filterable)',
        security: [['sanctum' => []]],
        tags: ['Pages'],
        parameters: [
            new OA\Parameter(name: 'search',   in: 'query', schema: new OA\Schema(type: 'string')),
            new OA\Parameter(name: 'menu_id',  in: 'query', schema: new OA\Schema(type: 'integer')),
            new OA\Parameter(name: 'status',   in: 'query', schema: new OA\Schema(type: 'string', enum: ['draft', 'published'])),
            new OA\Parameter(name: 'trashed',  in: 'query', schema: new OA\Schema(type: 'boolean')),
            new OA\Parameter(name: 'per_page', in: 'query', schema: new OA\Schema(type: 'integer')),
        ],
        responses: [new OA\Response(response: 200, description: 'Paginated pages list')]
    )]
    public function index(Request $request): JsonResponse
    {
        try {
            return api_response($this->pageService->list($request));
        } catch (\Throwable $e) {
            return api_response(null, $e->getMessage(), 500);
        }
    }

    #[OA\Post(
        path: '/api/pages',
        summary: 'Create a page',
        security: [['sanctum' => []]],
        tags: ['Pages'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\MediaType(
                mediaType: 'multipart/form-data',
                schema: new OA\Schema(
                    required: ['title', 'body', 'status'],
                    properties: [
                        new OA\Property(property: 'title',        type: 'string'),
                        new OA\Property(property: 'body',         type: 'string'),
                        new OA\Property(property: 'status',       type: 'string', enum: ['draft', 'published']),
                        new OA\Property(property: 'publish_date', type: 'string', format: 'date-time'),
                        new OA\Property(property: 'menu_id',      type: 'integer'),
                        new OA\Property(property: 'cover_image',  type: 'string', format: 'binary'),
                    ]
                )
            )
        ),
        responses: [new OA\Response(response: 201, description: 'Page created')]
    )]
    public function store(PageRequest $request): JsonResponse
    {
        try {
            return api_response($this->pageService->create($request->validated(), $request->file('cover_image'), $request->user()->id), 'Page created', 201);
        } catch (\Throwable $e) {
            return api_response(null, $e->getMessage(), 500);
        }
    }

    #[OA\Get(
        path: '/api/pages/{id}',
        summary: 'Get a page',
        security: [['sanctum' => []]],
        tags: ['Pages'],
        parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))],
        responses: [new OA\Response(response: 200, description: 'Page detail')]
    )]
    public function show(Page $page): JsonResponse
    {
        try {
            return api_response($this->pageService->show($page));
        } catch (\Throwable $e) {
            return api_response(null, $e->getMessage(), 500);
        }
    }

    #[OA\Post(
        path: '/api/pages/{id}',
        summary: 'Update a page',
        security: [['sanctum' => []]],
        tags: ['Pages'],
        parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))],
        requestBody: new OA\RequestBody(
            content: new OA\MediaType(
                mediaType: 'multipart/form-data',
                schema: new OA\Schema(properties: [
                    new OA\Property(property: 'title',        type: 'string'),
                    new OA\Property(property: 'body',         type: 'string'),
                    new OA\Property(property: 'status',       type: 'string', enum: ['draft', 'published']),
                    new OA\Property(property: 'publish_date', type: 'string', format: 'date-time'),
                    new OA\Property(property: 'menu_id',      type: 'integer'),
                    new OA\Property(property: 'cover_image',  type: 'string', format: 'binary'),
                ])
            )
        ),
        responses: [new OA\Response(response: 200, description: 'Page updated')]
    )]
    public function update(PageRequest $request, Page $page): JsonResponse
    {
        try {
            return api_response($this->pageService->update($page, $request->validated(), $request->file('cover_image'), $request->user()->id), 'Page updated');
        } catch (\Throwable $e) {
            return api_response(null, $e->getMessage(), 500);
        }
    }

    #[OA\Delete(
        path: '/api/pages/{id}',
        summary: 'Soft-delete a page',
        security: [['sanctum' => []]],
        tags: ['Pages'],
        parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))],
        responses: [new OA\Response(response: 200, description: 'Page deleted')]
    )]
    public function destroy(Page $page): JsonResponse
    {
        try {
            $this->pageService->delete($page);
            return api_response(null, 'Page deleted');
        } catch (\Throwable $e) {
            return api_response(null, $e->getMessage(), 500);
        }
    }

    #[OA\Post(
        path: '/api/pages/{id}/restore',
        summary: 'Restore a soft-deleted page',
        security: [['sanctum' => []]],
        tags: ['Pages'],
        parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))],
        responses: [new OA\Response(response: 200, description: 'Page restored')]
    )]
    public function restore(int $id): JsonResponse
    {
        try {
            return api_response($this->pageService->restore($id), 'Page restored');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException) {
            return api_response(null, 'Page not found or has not been deleted.', 404);
        } catch (\Throwable $e) {
            return api_response(null, $e->getMessage(), 500);
        }
    }
}
