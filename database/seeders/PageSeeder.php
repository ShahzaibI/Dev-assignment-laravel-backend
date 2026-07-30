<?php

namespace Database\Seeders;

use App\Models\Menu;
use App\Models\Page;
use App\Models\User;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    public function run(): void
    {
        $menus   = Menu::pluck('id');
        $userIds = User::pluck('id');

        $menus->each(function (int $menuId) use ($userIds) {
            Page::factory()->create([
                'menu_id'    => $menuId,
                'created_by' => fn() => $userIds->random(),
                'updated_by' => fn() => $userIds->random(),
            ]);
        });
    }
}
