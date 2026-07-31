<?php

namespace Database\Seeders;

use App\Models\Menu;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        $structure = [
            [
                'name' => 'Company', 'name_ar' => 'الشركة', 'sort_order' => 1,
                'children' => [
                    ['name' => 'About Us',    'name_ar' => 'من نحن'],
                    ['name' => 'Leadership',  'name_ar' => 'القيادة'],
                    ['name' => 'Careers',     'name_ar' => 'الوظائف'],
                    ['name' => 'Press',       'name_ar' => 'الصحافة'],
                ],
            ],
            [
                'name' => 'Products', 'name_ar' => 'المنتجات', 'sort_order' => 2,
                'children' => [
                    ['name' => 'Platform Overview', 'name_ar' => 'نظرة عامة على المنصة'],
                    ['name' => 'Integrations',      'name_ar' => 'التكاملات'],
                    ['name' => 'Pricing',           'name_ar' => 'الأسعار'],
                    ['name' => 'Changelog',         'name_ar' => 'سجل التغييرات'],
                ],
            ],
            [
                'name' => 'Resources', 'name_ar' => 'الموارد', 'sort_order' => 3,
                'children' => [
                    ['name' => 'Blog',          'name_ar' => 'المدونة'],
                    ['name' => 'Documentation', 'name_ar' => 'التوثيق'],
                    ['name' => 'Case Studies',  'name_ar' => 'دراسات الحالة'],
                    ['name' => 'Webinars',      'name_ar' => 'الندوات الإلكترونية'],
                ],
            ],
            [
                'name' => 'Support', 'name_ar' => 'الدعم', 'sort_order' => 4,
                'children' => [
                    ['name' => 'Help Center',   'name_ar' => 'مركز المساعدة'],
                    ['name' => 'Contact Us',    'name_ar' => 'اتصل بنا'],
                    ['name' => 'System Status', 'name_ar' => 'حالة النظام'],
                ],
            ],
            [
                'name' => 'Legal', 'name_ar' => 'القانونية', 'sort_order' => 5,
                'children' => [
                    ['name' => 'Privacy Policy',   'name_ar' => 'سياسة الخصوصية'],
                    ['name' => 'Terms of Service', 'name_ar' => 'شروط الخدمة'],
                    ['name' => 'Cookie Policy',    'name_ar' => 'سياسة ملفات تعريف الارتباط'],
                ],
            ],
        ];

        foreach ($structure as $item) {
            $parent = Menu::create([
                'name'       => $item['name'],
                'name_ar'    => $item['name_ar'],
                'parent_id'  => null,
                'sort_order' => $item['sort_order'],
            ]);

            foreach ($item['children'] as $i => $child) {
                Menu::create([
                    'name'       => $child['name'],
                    'name_ar'    => $child['name_ar'],
                    'parent_id'  => $parent->id,
                    'sort_order' => $i + 1,
                ]);
            }
        }
    }
}
