<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Cache;
use App\Models\Category;
use App\Models\Language;
use App\Models\Post;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer(['components.frontend-layout', 'welcome'], function ($view) {
            // Header Categories
            $view->with('headerCategories', Category::where('show_in_header', true)
                ->where('is_active', true)
                ->whereNull('parent_id')
                ->orderBy('order')
                ->get());

            // Available Languages
            $view->with('availableLanguages', Language::where('is_active', true)->get());
            
            // Breaking News (Ticker)
            $view->with('breakingNews', Post::where('status', 'published')
                ->where('is_trending', true)
                ->latest('published_at')
                ->limit(5)
                ->get());
            
            // Trending Articles (Algorithm Based + Cached)
            $view->with('sidebarTrendingArticles', \Illuminate\Support\Facades\Cache::remember('v2_trending_articles', 900, function () {
                return Post::trending(5)->get();
            }));
        });
    }
}
