<?php

namespace App\Services;

use App\Http\Resources\PageResource;
use App\Models\Page;
use App\Repositories\PageRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PageService
{
    public function __construct(private PageRepository $pageRepo) {}

    public function list(Request $request): array
    {
        $pages = $this->pageRepo->paginate($request);
        return PageResource::collection($pages)->response()->getData(true);
    }

    public function create(array $data, ?object $file, int $userId): PageResource
    {
        $data['created_by'] = $userId;
        $data['slug']       = $data['slug'] ?? Str::slug($data['title']);

        if ($file) {
            $data['cover_image'] = $file->store('covers', 'public');
        }

        return new PageResource($this->pageRepo->create($data));
    }

    public function update(Page $page, array $data, ?object $file, int $userId): PageResource
    {
        $data['updated_by'] = $userId;

        if ($file) {
            if ($page->cover_image) Storage::disk('public')->delete($page->cover_image);
            $data['cover_image'] = $file->store('covers', 'public');
        }

        return new PageResource($this->pageRepo->update($page, $data));
    }

    public function delete(Page $page): void
    {
        $this->pageRepo->delete($page);
    }

    public function restore(int $id): PageResource
    {
        $page = $this->pageRepo->findTrashed($id);
        return new PageResource($this->pageRepo->restore($page));
    }

    public function show(Page $page): PageResource
    {
        return new PageResource($page->load(['menu', 'creator', 'updater']));
    }
}
