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
            $view->with('latestPosts', Post::where('status', 'published')
                ->with(['category', 'translations', 'media', 'author'])
                ->latest('published_at')
                ->limit(10)   // hero(1) + right(2) = 3 consumed; rest available
                ->get());
        });
    }
}
