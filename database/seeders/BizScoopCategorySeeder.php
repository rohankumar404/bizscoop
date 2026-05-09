<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Str;

class BizScoopCategorySeeder extends Seeder
{
    public function run(): void
    {
        // First clear out existing categories if we are resetting the architecture
        // Note: Doing this might break existing posts if not handled. We will assume a fresh seed or we just update/create.
        
        $categories = [
            // Priority 1: Top Header & Hero Candidates
            [
                'name' => ['en' => 'Business', 'ar' => 'أعمال'],
                'color' => '#1da1f2',
                'mega_menu' => true,
                'hero_priority' => 10,
                'desktop_menu_order' => 1,
                'subcategories' => ['Corporate News', 'Mergers & Acquisitions', 'Entrepreneurship']
            ],
            [
                'name' => ['en' => 'Economy', 'ar' => 'اقتصاد'],
                'color' => '#28a745',
                'mega_menu' => true,
                'hero_priority' => 9,
                'desktop_menu_order' => 2,
            ],
            [
                'name' => ['en' => 'Markets', 'ar' => 'أسواق'],
                'color' => '#ffc107',
                'mega_menu' => true,
                'hero_priority' => 8,
                'desktop_menu_order' => 3,
                'subcategories' => ['Stocks', 'Forex', 'Commodities', 'IPOs']
            ],
            [
                'name' => ['en' => 'Technology', 'ar' => 'تكنولوجيا'],
                'color' => '#17a2b8',
                'mega_menu' => true,
                'hero_priority' => 7,
                'desktop_menu_order' => 4,
                'subcategories' => ['AI', 'Startups', 'Gadgets', 'Innovation', 'Cybersecurity']
            ],
            [
                'name' => ['en' => 'GCC News', 'ar' => 'أخبار الخليج'],
                'color' => '#6f42c1',
                'mega_menu' => true,
                'hero_priority' => 6,
                'desktop_menu_order' => 5,
            ],

            // Priority 2: High Value Niches
            [
                'name' => ['en' => 'Real Estate', 'ar' => 'عقارات'],
                'color' => '#e83e8c',
                'hero_priority' => 5,
                'desktop_menu_order' => 6,
                'subcategories' => ['Property News', 'Commercial Real Estate', 'Luxury Developments']
            ],
            [
                'name' => ['en' => 'Leadership', 'ar' => 'قيادة'],
                'color' => '#fd7e14',
                'hero_priority' => 4,
                'desktop_menu_order' => 7,
                'subcategories' => ['CEOs', 'Executive Interviews', 'Leadership Strategies']
            ],
            [
                'name' => ['en' => 'Startups', 'ar' => 'شركات ناشئة'],
                'color' => '#20c997',
                'hero_priority' => 3,
                'desktop_menu_order' => 8,
            ],

            // Priority 3: Finance & Energy
            [
                'name' => ['en' => 'Banking & Finance', 'ar' => 'بنوك وتمويل'],
                'desktop_menu_order' => 9,
            ],
            [
                'name' => ['en' => 'Investment', 'ar' => 'استثمار'],
                'desktop_menu_order' => 10,
            ],
            [
                'name' => ['en' => 'Crypto & Fintech', 'ar' => 'عملات مشفرة وتكنولوجيا مالية'],
                'desktop_menu_order' => 11,
            ],
            [
                'name' => ['en' => 'Energy', 'ar' => 'طاقة'],
                'desktop_menu_order' => 12,
            ],
            [
                'name' => ['en' => 'Oil & Gas', 'ar' => 'نفط وغاز'],
                'desktop_menu_order' => 13,
            ],

            // Priority 4: Regions
            [
                'name' => ['en' => 'UAE', 'ar' => 'الإمارات'],
                'desktop_menu_order' => 14,
            ],
            [
                'name' => ['en' => 'Saudi Arabia', 'ar' => 'السعودية'],
                'desktop_menu_order' => 15,
            ],
            [
                'name' => ['en' => 'Qatar', 'ar' => 'قطر'],
                'desktop_menu_order' => 16,
            ],
            [
                'name' => ['en' => 'Middle East', 'ar' => 'الشرق الأوسط'],
                'desktop_menu_order' => 17,
            ],
            [
                'name' => ['en' => 'Global Business', 'ar' => 'أعمال عالمية'],
                'desktop_menu_order' => 18,
            ],

            // Priority 5: Industry & Lifestyle
            [
                'name' => ['en' => 'Industry', 'ar' => 'صناعة'],
                'desktop_menu_order' => 19,
            ],
            [
                'name' => ['en' => 'Tourism & Hospitality', 'ar' => 'سياحة وضيافة'],
                'desktop_menu_order' => 20,
            ],
            [
                'name' => ['en' => 'Luxury', 'ar' => 'رفاهية'],
                'desktop_menu_order' => 21,
            ],
            [
                'name' => ['en' => 'Lifestyle', 'ar' => 'أسلوب حياة'],
                'desktop_menu_order' => 22,
            ],

            // Priority 6: Editorial & Features
            [
                'name' => ['en' => 'Events', 'ar' => 'فعاليات'],
                'desktop_menu_order' => 23,
            ],
            [
                'name' => ['en' => 'Opinion', 'ar' => 'رأي'],
                'desktop_menu_order' => 24,
            ],
            [
                'name' => ['en' => 'Interviews', 'ar' => 'مقابلات'],
                'desktop_menu_order' => 25,
            ],
            [
                'name' => ['en' => 'Magazine', 'ar' => 'مجلة'],
                'desktop_menu_order' => 26,
            ],
            [
                'name' => ['en' => 'Press Releases', 'ar' => 'بيانات صحفية'],
                'desktop_menu_order' => 27,
            ]
        ];

        $globalOrder = 1;

        foreach ($categories as $catData) {
            $cat = Category::updateOrCreate(
                ['slug' => Str::slug($catData['name']['en'])],
                [
                    'name' => $catData['name'],
                    'order' => $globalOrder++,
                    'is_active' => true,
                    'show_in_header' => ($catData['desktop_menu_order'] ?? 99) <= 8, // top 8 in header
                    'show_in_homepage' => true,
                    'hero_priority' => $catData['hero_priority'] ?? 0,
                    'desktop_menu_order' => $catData['desktop_menu_order'] ?? 99,
                    'mega_menu' => $catData['mega_menu'] ?? false,
                    'color' => $catData['color'] ?? null,
                    'enable_trending_section' => true,
                ]
            );

            if (!empty($catData['subcategories'])) {
                $subOrder = 1;
                foreach ($catData['subcategories'] as $sub) {
                    Category::updateOrCreate(
                        ['slug' => Str::slug($sub)],
                        [
                            'parent_id' => $cat->id,
                            'name' => ['en' => $sub, 'ar' => $sub],
                            'order' => $subOrder++,
                            'is_active' => true,
                            'show_in_header' => false,
                            'show_in_homepage' => false,
                        ]
                    );
                }
            }
        }
    }
}
