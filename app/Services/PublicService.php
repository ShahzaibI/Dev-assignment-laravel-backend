<?php

namespace App\Services;

use App\Http\Resources\MenuResource;
use App\Http\Resources\PageResource;
use App\Repositories\MenuRepository;
use App\Repositories\PageRepository;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class PublicService
{
    public function __construct(
        private MenuRepository $menuRepo,
        private PageRepository $pageRepo,
    ) {}

    public function menuTree(): AnonymousResourceCollection
    {
        return MenuResource::collection($this->menuRepo->publicTree());
    }

    public function publishedPage(string $slug): PageResource
    {
        return new PageResource($this->pageRepo->findPublishedBySlug($slug));
    }
}
