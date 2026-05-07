<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

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
        \Illuminate\Support\Facades\View::composer(['components.frontend-layout', 'welcome'], function ($view) {
            $view->with('headerCategories', \App\Models\Category::where('show_in_header', true)
                ->where('is_active', true)
                ->whereNull('parent_id')
                ->orderBy('order')
                ->get());
        });
    }
}
