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
            $usedHeroPostIds = collect();

            // Helper to fetch posts and track IDs
            $getHeroPosts = function($catSlug = null, $limit = 8, $onlyHero = false) use (&$usedHeroPostIds) {
                $query = Post::where('status', 'published')
                    ->with(['translations', 'media', 'author', 'category'])
                    ->latest('published_at');

                if ($catSlug) {
                    $query->whereHas('category', function($q) use ($catSlug) {
                        $q->where('slug', $catSlug);
                    });
                }

                if ($onlyHero) {
                    $query->where('is_hero', true);
                }

                $posts = $query->take($limit)->get();
                
                // Fallback if not enough posts
                if ($posts->count() < $limit) {
                    $fallbackQuery = Post::where('status', 'published')
                        ->whereNotIn('id', $posts->pluck('id'))
                        ->with(['translations', 'media', 'author', 'category'])
                        ->latest('published_at');
                    if ($catSlug) {
                        $fallbackQuery->whereHas('category', function($q) use ($catSlug) {
                            $q->where('slug', $catSlug);
                        });
                    }
                    $fallbackPosts = $fallbackQuery->take($limit - $posts->count())->get();
                    $posts = $posts->merge($fallbackPosts);
                }

                $usedHeroPostIds = $usedHeroPostIds->merge($posts->pluck('id'));
                return $posts->map(function ($p) {
                    $translated = $p->translate(app()->getLocale());
                    return [
                        'id'       => $p->id,
                        'title'    => $translated?->title ?? '',
                        'slug'     => $p->slug,
                        'category' => $p->category?->getTranslation('name', app()->getLocale()),
                        'author'   => $p->author?->name ?? 'Admin',
                        'date'     => $p->published_at?->format('M d, Y'),
                        'image'    => $p->getFirstMediaUrl('featured_image') ?: 'https://via.placeholder.com/800x600',
                        'excerpt'  => $translated?->excerpt ?? '',
                    ];
                });
            };

            $heroFeatured = $getHeroPosts(null, 8, true);
            $heroBusiness = $getHeroPosts('business', 8);
            $heroTechnology = $getHeroPosts('technology', 8);
            $heroMarkets = $getHeroPosts('markets', 8);

            $view->with([
                'heroFeatured'   => $heroFeatured,
                'heroBusiness'   => $heroBusiness,
                'heroTechnology' => $heroTechnology,
                'heroMarkets'    => $heroMarkets,
                'usedHeroPostIds'=> $usedHeroPostIds,
                'heroSettings'   => [
                    'box1_autoplay' => setting('hero_box1_autoplay', 1),
                    'box1_speed'    => setting('hero_box1_speed', 5000),
                    'box2_autoplay' => setting('hero_box2_autoplay', 1),
                    'box2_speed'    => setting('hero_box2_speed', 4000),
                    'box3_autoplay' => setting('hero_box3_autoplay', 1),
                    'box3_speed'    => setting('hero_box3_speed', 6000),
                    'box4_autoplay' => setting('hero_box4_autoplay', 1),
                    'box4_speed'    => setting('hero_box4_speed', 7000),
                ]
            ]);
        });
    }
}
