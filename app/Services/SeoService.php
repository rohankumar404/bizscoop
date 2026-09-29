<?php

namespace App\Services;

use App\Models\Setting;
use App\Models\Post;
use App\Models\Category;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

/**
 * Centralized SEO Service for BizScoopMENA.
 *
 * Responsibilities:
 *  - Canonical URL generation (always https://bizscoopmena.com/...)
 *  - Meta robots rules (per page type)
 *  - Sitemap eligibility
 *  - Indexability determination
 *  - SEO health warnings
 */
class SeoService
{
    // ────────────────────────────────────────────────────────────
    // SETTINGS HELPERS
    // ────────────────────────────────────────────────────────────

    /**
     * Return the canonical base URL from settings (e.g. https://bizscoopmena.com).
     * Falls back to APP_URL if no DB value exists, but always strips trailing slash.
     */
    public function canonicalBase(): string
    {
        $base = Cache::remember('seo_canonical_base', 300, function () {
            return Setting::get('seo_canonical_base', config('app.url', 'https://bizscoopmena.com'));
        });

        return rtrim($base, '/');
    }

    /**
     * Normalize any URL into the canonical format:
     *  - Use the configured canonical base (https://bizscoopmena.com)
     *  - Strip query parameters (UTM, ref, etc.) unless $keepQuery is true
     *  - Remove trailing slash (except homepage which becomes just /)
     */
    public function normalize(string $url, bool $keepQuery = false): string
    {
        $parsed = parse_url($url);
        $path   = $parsed['path'] ?? '/';

        // Remove trailing slash except for root
        if ($path !== '/' && Str::endsWith($path, '/')) {
            $path = rtrim($path, '/');
        }

        $canonical = $this->canonicalBase() . $path;

        if ($keepQuery && !empty($parsed['query'])) {
            $canonical .= '?' . $parsed['query'];
        }

        return $canonical;
    }

    // ────────────────────────────────────────────────────────────
    // CANONICAL URL GENERATORS
    // ────────────────────────────────────────────────────────────

    /**
     * Homepage canonical.
     */
    public function homepageCanonical(): string
    {
        return $this->canonicalBase() . '/';
    }

    /**
     * Article canonical from slug.
     */
    public function articleCanonical(string $slug): string
    {
        return $this->canonicalBase() . '/article/' . $slug;
    }

    /**
     * Section/Category canonical from slug.
     */
    public function sectionCanonical(string $slug): string
    {
        return $this->canonicalBase() . '/section/' . $slug;
    }

    /**
     * Static page canonical from path (e.g. 'about-us').
     */
    public function staticCanonical(string $path): string
    {
        return $this->canonicalBase() . '/' . ltrim($path, '/');
    }

    /**
     * Generic canonical from the current request path.
     * Strips query parameters automatically.
     */
    public function currentCanonical(): string
    {
        $path = request()->getPathInfo(); // e.g. /section/business
        if ($path === '/') {
            return $this->homepageCanonical();
        }
        $path = rtrim($path, '/') ?: '/';
        return $this->canonicalBase() . $path;
    }

    // ────────────────────────────────────────────────────────────
    // PER-PAGE SEO DATA
    // ────────────────────────────────────────────────────────────

    /**
     * Build a complete SEO data array for an article page.
     */
    public function forArticle(Post $post): array
    {
        $translation = $post->translate();
        $seoMeta     = $post->seoMeta;

        // Canonical: use manual override only if it is a valid absolute URL pointing to our domain
        $autoCanonical = $this->articleCanonical($post->slug);
        $canonical = $this->resolveCanonical($seoMeta?->canonical_url, $autoCanonical);

        $title       = (!empty($seoMeta?->meta_title))       ? $seoMeta->meta_title       : ($translation?->title ?? config('app.name'));
        $description = (!empty($seoMeta?->meta_description)) ? $seoMeta->meta_description : ($translation?->excerpt ?? '');
        $robots      = (!empty($seoMeta?->robots))           ? $seoMeta->robots           : $this->defaultRobots('article');
        $ogImage     = (!empty($seoMeta?->og_image))         ? $seoMeta->og_image         : $post->getFirstMediaUrl('featured_image');

        return compact('title', 'description', 'canonical', 'robots', 'ogImage');
    }

    /**
     * Build a complete SEO data array for a section/category page.
     */
    public function forSection(Category $category): array
    {
        $seoMeta  = $category->seoMeta;
        $locale   = app()->getLocale();

        $autoCanonical = $this->sectionCanonical($category->slug);
        $canonical = $this->resolveCanonical($seoMeta?->canonical_url, $autoCanonical);

        $title       = (!empty($seoMeta?->meta_title))       ? $seoMeta->meta_title       : $category->getTranslation('name', $locale);
        $description = (!empty($seoMeta?->meta_description)) ? $seoMeta->meta_description : ($category->getTranslation('description', $locale) ?? '');
        $robots      = (!empty($seoMeta?->robots))           ? $seoMeta->robots           : $this->defaultRobots('section');

        return compact('title', 'description', 'canonical', 'robots');
    }

    /**
     * Build SEO data for the homepage.
     */
    public function forHomepage(): array
    {
        $siteName    = Setting::get('site_name', config('app.name', 'BizScoopMENA'));
        $title       = Setting::get('default_meta_title', $siteName);
        $description = Setting::get('default_meta_description', '');
        $canonical   = $this->homepageCanonical();
        $robots      = $this->defaultRobots('homepage');

        return compact('title', 'description', 'canonical', 'robots');
    }

    /**
     * Build SEO data for static pages (about-us, careers, etc.)
     */
    public function forStaticPage(string $routePath, string $title = '', string $description = ''): array
    {
        $canonical = $this->staticCanonical($routePath);
        $robots    = $this->defaultRobots('static');

        return compact('title', 'description', 'canonical', 'robots');
    }

    /**
     * SEO data for utility/noindex pages (login, register, search, dashboard).
     */
    public function forUtilityPage(string $pageType, string $title = '', string $description = ''): array
    {
        $canonical = $this->currentCanonical();
        $robots    = $this->robotsForPageType($pageType);

        return compact('title', 'description', 'canonical', 'robots');
    }

    /**
     * Automatically determine and construct the appropriate SEO data
     * for the current HTTP request with resilient fallbacks.
     */
    public function forCurrentRequest(?\Illuminate\Http\Request $request = null): array
    {
        $request = $request ?? request();
        $path = trim($request->getPathInfo(), '/');

        // 1. Homepage
        if ($path === '' || $path === '/') {
            return $this->forHomepage();
        }

        // 2. Article route: /article/{slug}
        if (Str::startsWith($path, 'article/')) {
            $slug = Str::after($path, 'article/');
            $post = Post::where('slug', $slug)->first();
            if ($post) {
                return $this->forArticle($post);
            }
        }

        // 3. Section route: /section/{slug}
        if (Str::startsWith($path, 'section/')) {
            $slug = Str::after($path, 'section/');
            $category = Category::where('slug', $slug)->first();
            if ($category) {
                return $this->forSection($category);
            }
        }

        // 4. Utility / Auth / Search pages
        if ($path === 'login') {
            return $this->forUtilityPage('login', 'Sign In | ' . config('app.name', 'BizScoopMENA'));
        }
        if ($path === 'register') {
            return $this->forUtilityPage('register', 'Create Account | ' . config('app.name', 'BizScoopMENA'));
        }
        if ($path === 'search' || Str::startsWith($path, 'search')) {
            return $this->forUtilityPage('search', 'Search Results | ' . config('app.name', 'BizScoopMENA'));
        }
        if ($path === 'dashboard' || Str::startsWith($path, 'dashboard')) {
            return $this->forUtilityPage('dashboard', 'Dashboard | ' . config('app.name', 'BizScoopMENA'));
        }

        // 5. Known static pages
        $staticTitles = [
            'about-us'            => 'About Us | Premier Business Journalism',
            'editorial-standards' => 'Editorial Standards | Commitment to Truth',
            'advertise-with-us'   => 'Advertise With Us | Reach Industry Leaders',
            'careers'             => 'Careers | Join Our Newsroom',
            'contact-us'          => 'Contact Us | Get in Touch',
            'privacy-policy'      => 'Privacy Policy | Data Protection',
        ];

        if (isset($staticTitles[$path])) {
            return $this->forStaticPage($path, $staticTitles[$path]);
        }

        // 6. Generic safe fallback for any custom, dynamically added, or unrecognized route
        $siteName    = Setting::get('site_name', config('app.name', 'BizScoopMENA'));
        $title       = Setting::get('default_meta_title', $siteName);
        $description = Setting::get('default_meta_description', '');
        $canonical   = $this->currentCanonical();
        $robots      = $this->defaultRobots('default');

        return compact('title', 'description', 'canonical', 'robots');
    }

    // ────────────────────────────────────────────────────────────
    // ROBOTS META RULES
    // ────────────────────────────────────────────────────────────

    /**
     * Return the default robots string for a page type.
     */
    public function defaultRobots(string $pageType = 'default'): string
    {
        return $this->robotsForPageType($pageType);
    }

    public function robotsForPageType(string $pageType): string
    {
        $map = [
            'homepage' => Setting::get('seo_robots_default', 'index,follow'),
            'article'  => Setting::get('seo_robots_default', 'index,follow'),
            'section'  => Setting::get('seo_robots_default', 'index,follow'),
            'static'   => Setting::get('seo_robots_default', 'index,follow'),
            'login'    => Setting::get('seo_robots_login',    'noindex,follow'),
            'register' => Setting::get('seo_robots_register', 'noindex,follow'),
            'dashboard'=> Setting::get('seo_robots_dashboard','noindex,follow'),
            'search'   => Setting::get('seo_robots_search',   'noindex,follow'),
            'admin'    => 'noindex,nofollow',
            '404'      => 'noindex,follow',
            'error'    => 'noindex,follow',
        ];

        return $map[$pageType] ?? Setting::get('seo_robots_default', 'index,follow');
    }

    /**
     * Is this page indexable?
     */
    public function isIndexable(string $robots): bool
    {
        return !Str::contains(strtolower($robots), 'noindex');
    }

    // ────────────────────────────────────────────────────────────
    // SITEMAP ELIGIBILITY
    // ────────────────────────────────────────────────────────────

    /**
     * Should this article appear in sitemap?
     */
    public function articleInSitemap(Post $post): bool
    {
        if (!Setting::get('seo_sitemap_articles', '1')) return false;
        if ($post->status !== 'published') return false;
        if ($post->published_at > now()) return false;

        // If manual robots is set to noindex, exclude from sitemap
        $robots = $post->seoMeta?->robots ?? 'index,follow';
        if (!$this->isIndexable($robots)) return false;

        return true;
    }

    /**
     * Should this category appear in sitemap?
     */
    public function sectionInSitemap(Category $category): bool
    {
        if (!Setting::get('seo_sitemap_sections', '1')) return false;
        if (!$category->is_active) return false;

        $robots = $category->seoMeta?->robots ?? 'index,follow';
        if (!$this->isIndexable($robots)) return false;

        return true;
    }

    // ────────────────────────────────────────────────────────────
    // RELATED ARTICLES (Internal Linking)
    // ────────────────────────────────────────────────────────────

    /**
     * Get related posts for an article using manual admin internal links,
     * falling back to tag + category matching.
     */
    public function relatedPosts(Post $post, ?int $limit = null): \Illuminate\Database\Eloquent\Collection
    {
        if (!Setting::get('seo_related_articles_enabled', '1')) {
            return new \Illuminate\Database\Eloquent\Collection();
        }

        $limit = $limit ?? (int) Setting::get('seo_related_articles_count', 3);

        // 1. Manual admin internal links (Phase 13)
        $manualIds = $post->seoMeta?->internal_links['posts'] ?? [];
        $manualPosts = new \Illuminate\Database\Eloquent\Collection();
        if (!empty($manualIds) && is_array($manualIds)) {
            $manualPosts = Post::whereIn('id', $manualIds)
                ->where('status', 'published')
                ->where('published_at', '<=', now())
                ->where('id', '!=', $post->id)
                ->with(['translations', 'category'])
                ->get();

            if ($manualPosts->count() >= $limit) {
                return $manualPosts->take($limit);
            }
        }

        // 2. Backfill with automatic related posts (same category + shared tags)
        $needed = $limit - $manualPosts->count();
        $excludeIds = array_merge([$post->id], $manualPosts->pluck('id')->all());
        $tagIds = $post->tags->pluck('id');

        $autoPosts = Post::whereNotIn('id', $excludeIds)
            ->where('status', 'published')
            ->where('published_at', '<=', now())
            ->where(function ($q) use ($post, $tagIds) {
                $q->where('category_id', $post->category_id);
                if ($tagIds->isNotEmpty()) {
                    $q->orWhereHas('tags', fn($tq) => $tq->whereIn('tags.id', $tagIds));
                }
            })
            ->with(['translations', 'category'])
            ->orderByDesc('published_at')
            ->limit($needed)
            ->get();

        return $manualPosts->merge($autoPosts);
    }

    // ────────────────────────────────────────────────────────────
    // SEO HEALTH WARNINGS (Phase 15)
    // ────────────────────────────────────────────────────────────

    /**
     * Generate SEO health warnings for admin dashboard.
     * These run in admin context only — not on public requests.
     */
    public function healthWarnings(): array
    {
        $warnings = [];
        $base = $this->canonicalBase();

        // 1. Check canonical base is HTTPS
        if (!Str::startsWith($base, 'https://')) {
            $warnings[] = [
                'type'    => 'Protocol',
                'level'   => 'critical',
                'message' => 'Canonical base URL is not HTTPS (' . $base . '). Set seo_canonical_base to https://bizscoopmena.com',
            ];
        }

        // 2. Check canonical base has no www
        if (Str::contains($base, '://www.')) {
            $warnings[] = [
                'type'    => 'Host',
                'level'   => 'critical',
                'message' => 'Canonical base URL contains www (' . $base . '). Preferred format: https://bizscoopmena.com',
            ];
        }

        // 3. Trailing slash inconsistency in base setting
        $rawBase = Setting::get('seo_canonical_base', '');
        if (Str::endsWith($rawBase, '/') && $rawBase !== '/') {
            $warnings[] = [
                'type'    => 'URL Format',
                'level'   => 'warning',
                'message' => 'Canonical base setting contains a trailing slash. URLs should not have trailing slashes except root.',
            ];
        }

        // 4. Duplicate canonicals stored in database
        $duplicateCanonicals = \App\Models\SeoMeta::whereNotNull('canonical_url')
            ->where('canonical_url', '!=', '')
            ->select('canonical_url', \Illuminate\Support\Facades\DB::raw('COUNT(*) as count'))
            ->groupBy('canonical_url')
            ->having('count', '>', 1)
            ->get();
        if ($duplicateCanonicals->isNotEmpty()) {
            $warnings[] = [
                'type'    => 'Duplicate Canonical',
                'level'   => 'critical',
                'message' => "{$duplicateCanonicals->count()} duplicate canonical URL(s) detected across database entries.",
            ];
        }

        // 5. Articles missing meta description
        $noDesc = Post::where('status', 'published')
            ->where(function ($q) {
                $q->whereDoesntHave('seoMeta')
                  ->orWhereHas('seoMeta', fn($mq) => $mq->whereNull('meta_description')->orWhere('meta_description', ''));
            })
            ->count();
        if ($noDesc > 0) {
            $warnings[] = [
                'type'    => 'Meta Description',
                'level'   => 'warning',
                'message' => "{$noDesc} published article(s) are missing a meta description. Search engines will generate automated snippets.",
            ];
        }

        // 6. Meta description too long (> 160 characters)
        $longDesc = \App\Models\SeoMeta::whereNotNull('meta_description')
            ->whereRaw('CHAR_LENGTH(meta_description) > 160')
            ->count();
        if ($longDesc > 0) {
            $warnings[] = [
                'type'    => 'Meta Description',
                'level'   => 'info',
                'message' => "{$longDesc} article(s) have meta descriptions longer than 160 characters (may be truncated in SERPs).",
            ];
        }

        // 7. SEO Title too long (> 60 characters)
        $longTitle = \App\Models\SeoMeta::whereNotNull('meta_title')
            ->whereRaw('CHAR_LENGTH(meta_title) > 60')
            ->count();
        if ($longTitle > 0) {
            $warnings[] = [
                'type'    => 'SEO Title',
                'level'   => 'info',
                'message' => "{$longTitle} article(s) have SEO titles longer than 60 characters (may be truncated in SERPs).",
            ];
        }

        // 8. Articles with HTTP canonical in seo_meta
        $httpCanonicals = \App\Models\SeoMeta::where('seoable_type', Post::class)
            ->where('canonical_url', 'like', 'http://%')
            ->count();
        if ($httpCanonicals > 0) {
            $warnings[] = [
                'type'    => 'Insecure Canonical',
                'level'   => 'critical',
                'message' => "{$httpCanonicals} article(s) have an insecure HTTP (non-HTTPS) canonical URL stored.",
            ];
        }

        // 9. Articles with www in canonical
        $wwwCanonicals = \App\Models\SeoMeta::where('seoable_type', Post::class)
            ->where('canonical_url', 'like', '%://www.%')
            ->count();
        if ($wwwCanonicals > 0) {
            $warnings[] = [
                'type'    => 'WWW Canonical',
                'level'   => 'warning',
                'message' => "{$wwwCanonicals} article(s) have a www canonical URL stored — these should use non-www.",
            ];
        }

        // 10. Duplicate article slugs
        $duplicateSlugs = Post::select('slug', \Illuminate\Support\Facades\DB::raw('COUNT(*) as count'))
            ->groupBy('slug')
            ->having('count', '>', 1)
            ->get();
        if ($duplicateSlugs->isNotEmpty()) {
            $warnings[] = [
                'type'    => 'Duplicate Slug',
                'level'   => 'critical',
                'message' => "{$duplicateSlugs->count()} duplicate article slug(s) detected.",
            ];
        }

        // 11. Sections with fewer than minimum articles (Phase 14)
        $minArticles = (int) Setting::get('seo_min_articles_for_section', 6);
        \App\Models\Category::where('is_active', true)->withCount(['posts' => function ($q) {
            $q->where('status', 'published')->where('published_at', '<=', now());
        }])->get()->each(function ($cat) use (&$warnings, $minArticles) {
            if ($cat->posts_count < $minArticles) {
                $isRealEstate = Str::contains(strtolower($cat->slug), 'real-estate');
                $warnings[] = [
                    'type'    => 'Thin Section Content',
                    'level'   => 'warning',
                    'message' => "Section \"{$cat->name}\" has only {$cat->posts_count} published article(s). Recommended minimum: {$minArticles} for strong organic indexing."
                        . ($isRealEstate ? ' (Real Estate section has fewer than the recommended number of articles for strong organic indexing.)' : ''),
                ];
            }
        });

        return $warnings;
    }

    // ────────────────────────────────────────────────────────────
    // PRIVATE HELPERS
    // ────────────────────────────────────────────────────────────

    /**
     * Resolve canonical URL: use manual override only if it is a valid
     * absolute URL that starts with our canonical base. Otherwise return auto.
     */
    private function resolveCanonical(?string $manual, string $auto): string
    {
        if (!empty($manual) && filter_var($manual, FILTER_VALIDATE_URL)) {
            $base = $this->canonicalBase();
            // Accept if starts with our base (handles https://bizscoopmena.com/...)
            if (Str::startsWith($manual, $base)) {
                return rtrim($manual, '/') ?: $manual;
            }
        }
        return $auto;
    }
}
