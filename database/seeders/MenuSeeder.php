<?php

namespace Database\Seeders;

use App\Models\Menu;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        $structure = [
            ['name' => 'Company',    'sort_order' => 1, 'children' => ['About Us', 'Leadership', 'Careers', 'Press']],
            ['name' => 'Products',   'sort_order' => 2, 'children' => ['Platform Overview', 'Integrations', 'Pricing', 'Changelog']],
            ['name' => 'Resources',  'sort_order' => 3, 'children' => ['Blog', 'Documentation', 'Case Studies', 'Webinars']],
            ['name' => 'Support',    'sort_order' => 4, 'children' => ['Help Center', 'Contact Us', 'System Status']],
            ['name' => 'Legal',      'sort_order' => 5, 'children' => ['Privacy Policy', 'Terms of Service', 'Cookie Policy']],
        ];

        foreach ($structure as $item) {
            $parent = Menu::create([
                'name'       => $item['name'],
                'parent_id'  => null,
                'sort_order' => $item['sort_order'],
            ]);

            foreach ($item['children'] as $i => $childName) {
                Menu::create([
                    'name'       => $childName,
                    'parent_id'  => $parent->id,
                    'sort_order' => $i + 1,
                ]);
            }
        }
    }
}
