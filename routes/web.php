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
        $videos = \App\Models\Video::where('is_active', true)->latest()->get();
        return view('welcome', compact('videos'));
    })->name('home');

    Route::get('/section/{slug}', [\App\Http\Controllers\Frontend\CategoryController::class, 'show'])->name('category.show');

    Route::get('/article/{slug}', [\App\Http\Controllers\Frontend\PostController::class, 'show'])->name('article.show');

    // Search
    Route::get('/search', [\App\Http\Controllers\Frontend\SearchController::class, 'index'])->name('search');
    Route::get('/api/search/live', [\App\Http\Controllers\Frontend\SearchController::class, 'live'])->name('search.live');

    // Static Pages
    Route::get('/about-us', [\App\Http\Controllers\Frontend\PageController::class, 'about'])->name('pages.about');
    Route::get('/editorial-standards', [\App\Http\Controllers\Frontend\PageController::class, 'editorial'])->name('pages.editorial');
    Route::get('/advertise-with-us', [\App\Http\Controllers\Frontend\PageController::class, 'advertise'])->name('pages.advertise');
    Route::get('/careers', [\App\Http\Controllers\Frontend\PageController::class, 'careers'])->name('pages.careers');
    Route::get('/contact-us', [\App\Http\Controllers\Frontend\PageController::class, 'contact'])->name('pages.contact');
    Route::get('/privacy-policy', [\App\Http\Controllers\Frontend\PageController::class, 'privacy'])->name('pages.privacy');

    Route::get('/sitemap.xml', [\App\Http\Controllers\Frontend\SitemapController::class, 'index'])->name('sitemap');

    // Inquiries & Newsletter
    Route::post('/contact/store', [\App\Http\Controllers\Frontend\InquiryController::class, 'contactStore'])->name('contact.store');
    Route::post('/advertise/store', [\App\Http\Controllers\Frontend\InquiryController::class, 'advertiseStore'])->name('advertise.store');
    Route::post('/service-inquiry/store', [\App\Http\Controllers\Frontend\InquiryController::class, 'serviceInquiryStore'])->name('service-inquiry.store');
    Route::post('/newsletter/subscribe', [\App\Http\Controllers\Frontend\InquiryController::class, 'newsletterStore'])->name('newsletter.subscribe');
});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        $stats = [
            'posts' => \App\Models\Post::count(),
            'categories' => \App\Models\Category::count(),
            'tags' => \App\Models\Tag::count(),
            'ads' => \App\Models\Ad::count(),
            'leads' => \App\Models\Lead::count(),
            'subscribers' => \App\Models\Subscriber::count(),
        ];
        
        $recentPosts = \App\Models\Post::with(['category', 'author'])
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentPosts'));
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

    // Article & News Management
    Route::resource('posts', \App\Http\Controllers\Admin\PostController::class);
    Route::get('news/create', [\App\Http\Controllers\Admin\PostController::class, 'create'])->name('news.create');
    
    // Magazine Management
    Route::resource('magazines', \App\Http\Controllers\Admin\MagazineController::class);

    // Video Management
    Route::resource('videos', \App\Http\Controllers\Admin\VideoController::class);

    Route::resource('tags', \App\Http\Controllers\Admin\TagController::class)->except(['show', 'create', 'edit']);

    // Marketing & Management
    Route::get('newsletters', [\App\Http\Controllers\Admin\NewsletterController::class, 'index'])->name('newsletters.index');
    Route::post('newsletters/{subscriber}/toggle', [\App\Http\Controllers\Admin\NewsletterController::class, 'toggleStatus'])->name('newsletters.toggle');
    Route::delete('newsletters/{subscriber}', [\App\Http\Controllers\Admin\NewsletterController::class, 'destroy'])->name('newsletters.destroy');

    Route::resource('ads', \App\Http\Controllers\Admin\AdController::class);

    // Job Posting Management
    Route::resource('jobs', \App\Http\Controllers\Admin\JobController::class);

    // Lead Management
    Route::get('leads', [\App\Http\Controllers\Admin\LeadController::class, 'index'])->name('leads.index');
    Route::get('leads/{lead}', [\App\Http\Controllers\Admin\LeadController::class, 'show'])->name('leads.show');
    Route::delete('leads/{lead}', [\App\Http\Controllers\Admin\LeadController::class, 'destroy'])->name('leads.destroy');

    // Global Settings
    Route::get('settings', [\App\Http\Controllers\Admin\SettingController::class, 'index'])->name('settings.index');
    Route::put('settings', [\App\Http\Controllers\Admin\SettingController::class, 'update'])->name('settings.update');
    Route::post('settings/clear-cache', [\App\Http\Controllers\Admin\SettingController::class, 'clearCache'])->name('settings.clear-cache');

    // Profile & Credentials
    Route::get('profile', [\App\Http\Controllers\Admin\ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('profile', [\App\Http\Controllers\Admin\ProfileController::class, 'update'])->name('profile.update');
    
    // Legacy Breeze profile routes (can be removed if not needed)
    // Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    // Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    // Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
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
