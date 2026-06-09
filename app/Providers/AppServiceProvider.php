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
// Fix storage URLs on cPanel subfolder deployment
config([
        'filesystems.disks.public.url'
            => env('APP_URL').'/storage'
    ]);
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

            // Helper: map a post to a simple array for Alpine.js
            $mapPost = function ($p) {
                $translated = $p->translate(app()->getLocale());
                return [
                    'id'       => $p->id,
                    'title'    => $translated?->title ?? '',
                    'slug'     => $p->slug,
                    'category' => $p->category?->getTranslation('name', app()->getLocale()) ?? '',
                    'author'   => $p->author?->name ?? 'Admin',
                    'date'     => $p->published_at?->format('M d, Y') ?? '',
                    'image'    => $p->getFirstMediaUrl('featured_image') ?: null,
                    'excerpt'  => $translated?->excerpt ?? '',
                ];
            };

            // Helper: fetch posts with optional category IDs, optional flags, excluding IDs
            $fetchPosts = function($limit, $catIds = [], $onlyFeatured = false) use (&$usedHeroPostIds, $mapPost) {
                $query = Post::where('status', 'published')
                    ->where('published_at', '<=', now())
                    ->whereNotIn('id', $usedHeroPostIds->all())
                    ->with(['translations', 'media', 'author', 'category'])
                    ->latest('published_at');

                if (!empty($catIds)) {
                    $query->whereIn('category_id', $catIds);
                }

                if ($onlyFeatured) {
                    $query->where(function($q) {
                        $q->where('is_featured', true)->orWhere('is_hero', true);
                    });
                }

                $posts = $query->take($limit)->get();

                // Fallback: if still not enough, get latest published (any category)
                if ($posts->count() < $limit) {
                    $fallback = Post::where('status', 'published')
                        ->where('published_at', '<=', now())
                        ->whereNotIn('id', $usedHeroPostIds->merge($posts->pluck('id'))->all())
                        ->with(['translations', 'media', 'author', 'category'])
                        ->latest('published_at')
                        ->take($limit - $posts->count())
                        ->get();
                    $posts = $posts->merge($fallback);
                }

                // Filter out posts with no image, with a secondary fallback
                $withImage = $posts->filter(fn($p) => $p->getFirstMediaUrl('featured_image') !== '');
                if ($withImage->count() < $limit) {
                    $withImage = $posts; // Accept posts without images rather than showing blank box
                }

                $usedHeroPostIds = $usedHeroPostIds->merge($posts->pluck('id'));

                return $withImage->take($limit)->map($mapPost)->values();
            };

            // BOX 1: Last 5 featured/hero articles
            $heroFeatured = $fetchPosts(5, [], true);

            // BOX 2: Last 5 latest articles (global, not repeated)
            $heroLatest = $fetchPosts(5, [], false);

            // BOX 3: Business category + subcategories, last 5
            $businessCat = Category::where('slug', 'business')->where('is_active', true)->first();
            $businessIds = $businessCat
                ? Category::where(fn($q) => $q->where('id', $businessCat->id)->orWhere('parent_id', $businessCat->id))
                    ->where('is_active', true)->pluck('id')->toArray()
                : [];
            $heroBusiness = $fetchPosts(5, $businessIds, false);

            // BOX 4: Economy category + subcategories, last 5
            $economyCat = Category::where('slug', 'economy')->where('is_active', true)->first();
            $economyIds = $economyCat
                ? Category::where(fn($q) => $q->where('id', $economyCat->id)->orWhere('parent_id', $economyCat->id))
                    ->where('is_active', true)->pluck('id')->toArray()
                : [];
            $heroEconomy = $fetchPosts(5, $economyIds, false);

            $view->with([
                'heroFeatured'    => $heroFeatured,
                'heroLatest'      => $heroLatest,
                'heroBusiness'    => $heroBusiness,
                'heroEconomy'     => $heroEconomy,
                'usedHeroPostIds' => $usedHeroPostIds,
                'heroSettings'    => [
                    'box1_autoplay' => setting('hero_box1_autoplay', 1),
                    'box1_speed'    => setting('hero_box1_speed', 5000),
                    'box2_autoplay' => setting('hero_box2_autoplay', 1),
                    'box2_speed'    => setting('hero_box2_speed', 4500),
                    'box3_autoplay' => setting('hero_box3_autoplay', 1),
                    'box3_speed'    => setting('hero_box3_speed', 5500),
                    'box4_autoplay' => setting('hero_box4_autoplay', 1),
                    'box4_speed'    => setting('hero_box4_speed', 6000),
                ],
                'businessCatSlug' => $businessCat?->slug ?? 'business',
                'economyCatSlug'  => $economyCat?->slug ?? 'economy',
            ]);
        });

        // ── Dynamic SMTP Configuration ─────────────────────────────────────
        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('settings')) {
                config([
                    'mail.mailers.smtp.transport' => setting('mail_mailer', config('mail.mailers.smtp.transport', 'smtp')),
                    'mail.mailers.smtp.host' => setting('mail_host', config('mail.mailers.smtp.host', 'smtp.mailtrap.io')),
                    'mail.mailers.smtp.port' => setting('mail_port', config('mail.mailers.smtp.port', '2525')),
                    'mail.mailers.smtp.username' => setting('mail_username', config('mail.mailers.smtp.username')),
                    'mail.mailers.smtp.password' => setting('mail_password', config('mail.mailers.smtp.password')),
                    'mail.mailers.smtp.encryption' => setting('mail_encryption', config('mail.mailers.smtp.encryption', 'tls')),
                    'mail.from.address' => setting('mail_from_address', config('mail.from.address', 'hello@bizscoop.com')),
                    'mail.from.name' => setting('mail_from_name', config('mail.from.name', 'BizScoop')),
                ]);
            }
        } catch (\Exception $e) {
            // Silence exceptions when settings table does not exist or database is offline during bootstrapping
        }
    }
}
