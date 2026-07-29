<?php

namespace Database\Seeders;

use App\Models\Menu;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        Menu::factory(5)->create()->each(function (Menu $parent, int $i) {
            Menu::factory(rand(1, 3))->create([
                'parent_id'  => $parent->id,
                'sort_order' => $i,
            ]);
        });
    }
}
