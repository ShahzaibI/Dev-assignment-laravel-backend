<?php

namespace App\Repositories;

use App\Models\Menu;
use Illuminate\Database\Eloquent\Collection;

class MenuRepository
{
    public function tree(): Collection
    {
        return Menu::with('children')
            ->whereNull('parent_id')
            ->orderBy('sort_order')
            ->get();
    }

    public function publicTree(): Collection
    {
        return Menu::with([
            'pages' => fn($q) => $q->published()->select('id', 'title', 'title_ar', 'slug', 'menu_id', 'cover_image', 'publish_date'),
            'children.pages' => fn($q) => $q->published()->select('id', 'title', 'title_ar', 'slug', 'menu_id', 'cover_image', 'publish_date'),
        ])->whereNull('parent_id')->orderBy('sort_order')->get();
    }

    public function create(array $data): Menu
    {
        return Menu::create($data);
    }

    public function update(Menu $menu, array $data): Menu
    {
        $menu->update($data);
        return $menu;
    }

    public function delete(Menu $menu): void
    {
        $menu->delete();
    }

    public function reorder(array $items): void
    {
        foreach ($items as $item) {
            Menu::where('id', $item['id'])->update([
                'sort_order' => $item['sort_order'],
                'parent_id'  => $item['parent_id'] ?? null,
            ]);
        }
    }
}
