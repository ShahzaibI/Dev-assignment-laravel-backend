<?php

namespace App\Services;

use App\Http\Resources\MenuResource;
use App\Models\Menu;
use App\Repositories\MenuRepository;

class MenuService
{
    public function __construct(private MenuRepository $menuRepo) {}

    public function tree(): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        return MenuResource::collection($this->menuRepo->tree());
    }

    public function create(array $data): MenuResource
    {
        return new MenuResource($this->menuRepo->create($data));
    }

    public function update(Menu $menu, array $data): MenuResource
    {
        return new MenuResource($this->menuRepo->update($menu, $data));
    }

    public function delete(Menu $menu): void
    {
        $this->menuRepo->delete($menu);
    }

    public function reorder(array $items): void
    {
        $this->menuRepo->reorder($items);
    }
}
