<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Frontend Routes
|--------------------------------------------------------------------------
*/
Route::name('frontend.')->group(function () {
    Route::get('/', function () {
        return view('welcome');
    })->name('home');

    Route::get('/section/{slug}', [\App\Http\Controllers\Frontend\CategoryController::class, 'show'])->name('category.show');

    Route::get('/article/{slug}', [\App\Http\Controllers\Frontend\PostController::class, 'show'])->name('article.show');

    // Search
    Route::get('/search', [\App\Http\Controllers\Frontend\SearchController::class, 'index'])->name('search');
    Route::get('/api/search/live', [\App\Http\Controllers\Frontend\SearchController::class, 'live'])->name('search.live');

    Route::get('/sitemap.xml', [\App\Http\Controllers\Frontend\SitemapController::class, 'index'])->name('sitemap');
});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    // ── Category Management ─────────────────────────────────────
    // Static routes MUST come before the resource routes to prevent
    // 'trash', 'bulk', 'update-order' being captured as {category}
    Route::get('categories/trash',                [\App\Http\Controllers\Admin\CategoryController::class, 'trash'])->name('categories.trash');
    Route::post('categories/update-order',        [\App\Http\Controllers\Admin\CategoryController::class, 'updateOrder'])->name('categories.update-order');
    Route::post('categories/bulk',                [\App\Http\Controllers\Admin\CategoryController::class, 'bulk'])->name('categories.bulk');
    Route::post('categories/{id}/restore',        [\App\Http\Controllers\Admin\CategoryController::class, 'restore'])->name('categories.restore');
    Route::delete('categories/{id}/force-delete', [\App\Http\Controllers\Admin\CategoryController::class, 'forceDelete'])->name('categories.force-delete');
    Route::post('categories/{category}/toggle',   [\App\Http\Controllers\Admin\CategoryController::class, 'toggle'])->name('categories.toggle');
    Route::resource('categories',                  \App\Http\Controllers\Admin\CategoryController::class);

    // Article Management
    Route::resource('posts', \App\Http\Controllers\Admin\PostController::class);
    Route::resource('tags', \App\Http\Controllers\Admin\TagController::class)->except(['show', 'create', 'edit']);

    // Marketing & Management
    Route::get('newsletters', [\App\Http\Controllers\Admin\NewsletterController::class, 'index'])->name('newsletters.index');
    Route::post('newsletters/{subscriber}/toggle', [\App\Http\Controllers\Admin\NewsletterController::class, 'toggleStatus'])->name('newsletters.toggle');
    Route::delete('newsletters/{subscriber}', [\App\Http\Controllers\Admin\NewsletterController::class, 'destroy'])->name('newsletters.destroy');

    Route::resource('ads', \App\Http\Controllers\Admin\AdController::class);

    // Global Settings
    Route::get('settings', [\App\Http\Controllers\Admin\SettingController::class, 'index'])->name('settings.index');
    Route::put('settings', [\App\Http\Controllers\Admin\SettingController::class, 'update'])->name('settings.update');
    Route::post('settings/clear-cache', [\App\Http\Controllers\Admin\SettingController::class, 'clearCache'])->name('settings.clear-cache');

    // Profile routes within admin
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| Multilingual Support
|--------------------------------------------------------------------------
*/
Route::get('locale/{locale}', function ($locale) {
    if (array_key_exists($locale, config('app.available_locales', ['en' => 'English']))) {
        session()->put('locale', $locale);
    }
    return redirect()->back();
})->name('locale.switch');

require __DIR__.'/auth.php';
