<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\Category;
use App\Models\Post;
use App\Models\PostTranslation;
use App\Models\Tag;
use App\Models\SeoMeta;
use App\Models\User;

class BizScoopDemoSeeder extends Seeder
{
    private string $defaultImage = 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?w=800&q=80';

    private array $catImages = [
        'business' => 'https://images.unsplash.com/photo-1507679799987-c73779587ccf?w=800&q=80',
        'economy' => 'https://images.unsplash.com/photo-1611974789855-9c2a0a7236a3?w=800&q=80',
        'markets' => 'https://images.unsplash.com/photo-1590283603385-17ffb3a7f29f?w=800&q=80',
        'startups' => 'https://images.unsplash.com/photo-1559136555-9303baea8ebd?w=800&q=80',
        'technology' => 'https://images.unsplash.com/photo-1518770660439-4636190af475?w=800&q=80',
        'real-estate' => 'https://images.unsplash.com/photo-1560518883-ce09059eeffa?w=800&q=80',
        'leadership' => 'https://images.unsplash.com/photo-1542744173-8e7e53415bb0?w=800&q=80',
        'gcc-news' => 'https://images.unsplash.com/photo-1512453979798-5ea266f8880c?w=800&q=80',
        'mena-news' => 'https://images.unsplash.com/photo-1531297484001-80022131f5a1?w=800&q=80',
        'finance' => 'https://images.unsplash.com/photo-1559526324-593bc073d938?w=800&q=80',
        'investment' => 'https://images.unsplash.com/photo-1556761175-b413da4baf72?w=800&q=80',
        'energy' => 'https://images.unsplash.com/photo-1473341304170-971dccb5ac1e?w=800&q=80',
        'ai-innovation' => 'https://images.unsplash.com/photo-1620712943543-bcc4688e7485?w=800&q=80',
        'luxury' => 'https://images.unsplash.com/photo-1583121274602-3e2820c69888?w=800&q=80',
        'industry' => 'https://images.unsplash.com/photo-1581091226825-a6a2a5aee158?w=800&q=80',
        'global-affairs' => 'https://images.unsplash.com/photo-1526481280693-3bfa7568e0f3?w=800&q=80',
        'opinion' => 'https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?w=800&q=80',
        'interviews' => 'https://images.unsplash.com/photo-1551836022-d5d88e9218df?w=800&q=80',
        'magazines' => 'https://images.unsplash.com/photo-1585241936939-f9c12239d10e?w=800&q=80',
        'videos' => 'https://images.unsplash.com/photo-1492691527719-9d1e07e534b4?w=800&q=80',
    ];

    public function run(): void
    {
        $author = User::first() ?? User::factory()->create();

        // Fetch ALL categories created by BizScoopCategorySeeder
        $categories = Category::all();

        foreach ($categories as $cat) {
            $this->command->info("Seeding posts for category: {$cat->getTranslation('name', 'en')}");

            // Seed more articles for Business to support the new slider feature, 5 for others
            $articleCount = ($cat->getTranslation('name', 'en') === 'Business') ? 15 : 5;
            for ($i = 1; $i <= $articleCount; $i++) {
                $catNameEn = $cat->getTranslation('name', 'en');
                $title = "Premium {$catNameEn} Article {$i}: How Leaders are Adapting in 2026";
                $slug = Str::slug($title) . '-' . rand(100, 9999);
                
                $post = Post::create([
                    'author_id'      => $author->id,
                    'category_id'    => $cat->id,
                    'slug'           => $slug,
                    'status'         => 'published',
                    'type'           => 'article',
                    'published_at'   => Carbon::now()->subDays(rand(0, 30)),
                    'views'          => rand(500, 15000),
                    'trending_score' => rand(10, 100),
                    'reading_time'   => rand(3, 12),
                    'is_trending'    => ($i === 1),
                    'is_featured'    => ($i === 2),
                    'is_hero'        => ($i === 3 && $cat->hero_priority > 5), // Auto-assign some hero posts
                ]);

                PostTranslation::create([
                    'post_id' => $post->id,
                    'locale'  => 'en',
                    'title'   => $title,
                    'excerpt' => "An exclusive look into the {$catNameEn} sector, analyzing market trends, global impact, and what executives need to know for the upcoming fiscal quarter.",
                    'content' => "**Key insights into the {$catNameEn} sector.**\n\nExperts agree that recent shifts will reshape the industry.",
                ]);

                // If subcategory, fallback to default image to save bandwidth/time, or use parent if we wanted.
                // Here we just use defaultImage if not specifically listed.
                $imgUrl = $this->catImages[$cat->slug] ?? $this->defaultImage;
                try {
                    $post->addMediaFromUrl($imgUrl)
                         ->withCustomProperties(['alt' => $title])
                         ->toMediaCollection('featured_image');
                } catch (\Exception $e) {
                    $this->command->warn("  Could not download image for: {$title}");
                }
            }
        }

        $this->command->info('Seeding Magazines...');
        for ($i = 1; $i <= 5; $i++) {
            $magTitle = "BizScoop Executive Issue {$i}";
            $magazine = \App\Models\Magazine::create([
                'title' => $magTitle,
                'issue_number' => "Vol {$i}.0",
                'published_at' => Carbon::now()->subMonths($i - 1),
                'is_active' => true,
            ]);

            try {
                $magazine->addMediaFromUrl($this->defaultImage)
                     ->withCustomProperties(['alt' => $magTitle])
                     ->toMediaCollection('cover_image');
            } catch (\Exception $e) {
                $this->command->warn("  Could not download image for: {$magTitle}");
            }
        }

        $this->command->info('✅ Premium demo data seeded successfully into MENA architecture!');
    }
}
