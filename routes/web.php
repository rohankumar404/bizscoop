<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

/**
 * Frontend Routes
 */
Route::name('frontend.')->group(function () {
    Route::get('/', function () {
        return view('welcome'); // Placeholder
    })->name('home');

    Route::get('/article/{slug}', function ($slug) {
        return "Article: $slug";
    })->name('article.show');
});

/**
 * Admin Routes
 */
Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard'); // Placeholder
    })->name('dashboard');

    Route::get('/articles', function () {
        return "Admin Articles List";
    })->name('articles.index');
});

/**
 * Multilingual Support
 */
Route::get('locale/{locale}', function ($locale) {
    if (array_key_exists($locale, config('app.available_locales', ['en' => 'English']))) {
        session()->put('locale', $locale);
    }
    return redirect()->back();
})->name('locale.switch');

// require __DIR__.'/auth.php';
