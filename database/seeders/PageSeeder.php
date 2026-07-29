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
        $menuIds = Menu::pluck('id');
        $userIds = User::pluck('id');

        Page::factory(10)->create([
            'menu_id'    => fn() => $menuIds->random(),
            'created_by' => fn() => $userIds->random(),
            'updated_by' => fn() => $userIds->random(),
        ]);
    }
}
