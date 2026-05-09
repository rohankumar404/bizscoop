<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Category;
use App\Models\Language;
use App\Models\Post;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        // ── Shared with every frontend view ──────────────────────────────
        View::composer(['components.frontend-layout', 'welcome', 'frontend.*'], function ($view) {

            $view->with('headerCategories', Category::where('show_in_header', true)
                ->where('is_active', true)
                ->whereNull('parent_id')
                ->orderBy('order')
                ->with('children')
                ->get());

            $view->with('availableLanguages', Language::where('is_active', true)->get());

            $view->with('breakingNews', Post::where('status', 'published')
                ->where('is_trending', true)
                ->with(['translations'])
                ->latest('published_at')
                ->limit(8)
                ->get());

            // Use model's trending() scope if it exists, otherwise fallback to latest
            try {
                $view->with('sidebarTrendingArticles', Post::trending(6)->with(['category', 'translations', 'media'])->get());
            } catch (\Exception $e) {
                $view->with('sidebarTrendingArticles', Post::where('status', 'published')
                    ->with(['category', 'translations', 'media'])
                    ->latest('published_at')
                    ->limit(6)
                    ->get());
            }
        });

        // ── Homepage only ─────────────────────────────────────────────────
        View::composer('welcome', function ($view) {
            // Hero posts grouped by category (for tabbed slider)
            $cats = \App\Models\Category::where('show_in_header', true)
                ->where('is_active', true)
                ->whereNull('parent_id')
                ->orderBy('order')
                ->get();

            $heroPostsByCategory = [];
            $usedHeroPostIds = collect();

            foreach ($cats as $cat) {
                // 1. Manual Hero selection
                $posts = $cat->posts()
                    ->where('status', 'published')
                    ->where('is_hero', true)
                    ->with(['translations', 'media', 'author', 'category'])
                    ->latest('published_at')
                    ->take(8)
                    ->get();
                
                $remaining = 8 - $posts->count();

                // 2. Fallback to Trending if priority > 0
                if ($remaining > 0 && $cat->hero_priority > 0) {
                    $trendingPosts = $cat->posts()
                        ->where('status', 'published')
                        ->where('is_trending', true)
                        ->whereNotIn('id', $posts->pluck('id'))
                        ->with(['translations', 'media', 'author', 'category'])
                        ->latest('published_at')
                        ->take($remaining)
                        ->get();
                    $posts = $posts->merge($trendingPosts);
                    $remaining = 8 - $posts->count();
                }

                // 3. Fallback to Latest
                if ($remaining > 0) {
                    $latestCatPosts = $cat->posts()
                        ->where('status', 'published')
                        ->whereNotIn('id', $posts->pluck('id'))
                        ->with(['translations', 'media', 'author', 'category'])
                        ->latest('published_at')
                        ->take($remaining)
                        ->get();
                    $posts = $posts->merge($latestCatPosts);
                }

                $usedHeroPostIds = $usedHeroPostIds->merge($posts->pluck('id'));

                $heroPostsByCategory[$cat->id] = $posts->map(function ($p) {
                    return [
                        'id'       => $p->id,
                        'slug'     => $p->slug,
                        'title'    => $p->translate()?->title ?? '',
                        'excerpt'  => $p->translate()?->excerpt ?? '',
                        'image'    => $p->getFirstMediaUrl('featured_image') ?: '',
                        'author'   => $p->author?->name ?? '',
                        'date'     => $p->published_at?->format('d M Y') ?? '',
                        'category' => $p->category?->getTranslation('name', 'en') ?? '',
                        'cat_slug' => $p->category?->slug ?? '',
                    ];
                });
            }

            $view->with('usedHeroPostIds', $usedHeroPostIds->unique()->values()->all());

            $view->with('latestPosts', Post::where('status', 'published')
                ->whereNotIn('id', $usedHeroPostIds->unique()->values()->all())
                ->with(['category', 'translations', 'media', 'author'])
                ->latest('published_at')
                ->limit(10)
                ->get());

            $view->with('heroPostsByCategory', $heroPostsByCategory);
            $view->with('heroCats', $cats);

            // Hero slider admin settings
            $view->with('heroSettings', [
                'box1_autoplay' => setting('hero_box1_autoplay', '1'),
                'box1_speed'    => setting('hero_box1_speed',    '5000'),
                'box2_autoplay' => setting('hero_box2_autoplay', '1'),
                'box2_speed'    => setting('hero_box2_speed',    '6000'),
                'box3_autoplay' => setting('hero_box3_autoplay', '1'),
                'box3_speed'    => setting('hero_box3_speed',    '7000'),
                'box4_autoplay' => setting('hero_box4_autoplay', '1'),
                'box4_speed'    => setting('hero_box4_speed',    '8000'),
            ]);
        });
    }
}
