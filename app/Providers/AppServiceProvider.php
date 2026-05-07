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
        // Data for the master layout
        View::composer('components.frontend-layout', function ($view) {
            $view->with('headerCategories', Category::where('show_in_header', true)
                ->where('is_active', true)
                ->whereNull('parent_id')
                ->orderBy('order')
                ->get());

            $view->with('availableLanguages', Language::where('is_active', true)->get());
            
            $view->with('breakingNews', Post::where('status', 'published')
                ->where('is_trending', true)
                ->latest('published_at')
                ->limit(5)
                ->get());
        });

        // Data specifically for the homepage
        View::composer('welcome', function ($view) {
            $view->with('sidebarTrendingArticles', Post::trending(5)->with('category')->get());
        });
    }
}
