<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Category;
use App\Services\SeoService;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

class SitemapController extends Controller
{
    public function index(SeoService $seo)
    {
        $sitemap = Sitemap::create();

        // ── Homepage ──────────────────────────────────────────────────────
        $sitemap->add(
            Url::create($seo->homepageCanonical())
               ->setPriority(1.0)
               ->setChangeFrequency(Url::CHANGE_FREQUENCY_ALWAYS)
        );

        // ── Static Pages ───────────────────────────────────────────────────
        if (\App\Models\Setting::get('seo_sitemap_static_pages', '1')) {
            $staticPages = [
                'about-us'         => ['priority' => 0.6, 'freq' => Url::CHANGE_FREQUENCY_MONTHLY],
                'editorial-standards' => ['priority' => 0.5, 'freq' => Url::CHANGE_FREQUENCY_MONTHLY],
                'advertise-with-us'=> ['priority' => 0.6, 'freq' => Url::CHANGE_FREQUENCY_MONTHLY],
                'careers'          => ['priority' => 0.6, 'freq' => Url::CHANGE_FREQUENCY_WEEKLY],
                'contact-us'       => ['priority' => 0.5, 'freq' => Url::CHANGE_FREQUENCY_MONTHLY],
                'privacy-policy'   => ['priority' => 0.3, 'freq' => Url::CHANGE_FREQUENCY_YEARLY],
            ];

            foreach ($staticPages as $path => $opts) {
                $sitemap->add(
                    Url::create($seo->staticCanonical($path))
                       ->setPriority($opts['priority'])
                       ->setChangeFrequency($opts['freq'])
                );
            }
        }

        // ── Sections / Categories ────────────────────────────────────────
        if (\App\Models\Setting::get('seo_sitemap_sections', '1')) {
            Category::where('is_active', true)->get()->each(function ($category) use ($sitemap, $seo) {
                if ($seo->sectionInSitemap($category)) {
                    $sitemap->add(
                        Url::create($seo->sectionCanonical($category->slug))
                           ->setPriority(0.8)
                           ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
                    );
                }
            });
        }

        // ── Articles ────────────────────────────────────────────────────────
        if (\App\Models\Setting::get('seo_sitemap_articles', '1')) {
            Post::where('status', 'published')
                ->where('published_at', '<=', now())
                ->with('seoMeta')
                ->orderBy('published_at', 'desc')
                ->get()
                ->each(function ($post) use ($sitemap, $seo) {
                    if ($seo->articleInSitemap($post)) {
                        $sitemap->add(
                            Url::create($seo->articleCanonical($post->slug))
                               ->setPriority(0.7)
                               ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                               ->setLastModificationDate($post->updated_at)
                        );
                    }
                });
        }

        return $sitemap->toResponse(request());
    }
}
