<?php

namespace App\Repositories;

use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class PageRepository
{
    public function paginate(Request $request): LengthAwarePaginator
    {
        return Page::with(['menu', 'creator', 'updater'])
            ->when($request->trashed, fn($q) => $q->onlyTrashed())
            ->when($request->search,  fn($q, $s) => $q->where('title', 'like', "%{$s}%"))
            ->when($request->menu_id, fn($q, $m) => $q->where('menu_id', $m))
            ->when($request->status,  fn($q, $s) => $q->where('status', $s))
            ->latest()
            ->paginate(10);
    }

    public function create(array $data): Page
    {
        return Page::create($data)->load(['menu', 'creator']);
    }

    public function update(Page $page, array $data): Page
    {
        $page->update($data);
        return $page->load(['menu', 'creator', 'updater']);
    }

    public function delete(Page $page): void
    {
        $page->delete();
    }

    public function findTrashed(int $id): Page
    {
        return Page::onlyTrashed()->findOrFail($id);
    }

    public function restore(Page $page): Page
    {
        $page->restore();
        return $page;
    }

    public function findPublishedBySlug(string $slug): Page
    {
        return Page::with(['menu', 'creator'])
            ->published()
            ->where('slug', $slug)
            ->firstOrFail();
    }
}
