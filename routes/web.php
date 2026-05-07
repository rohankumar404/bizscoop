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

    // Category Management
    Route::resource('categories', \App\Http\Controllers\Admin\CategoryController::class);
    Route::post('categories/update-order', [\App\Http\Controllers\Admin\CategoryController::class, 'updateOrder'])->name('categories.update-order');

    // Article Management
    Route::resource('posts', \App\Http\Controllers\Admin\PostController::class);

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
