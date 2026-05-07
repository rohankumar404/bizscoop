<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Category;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

class SitemapController extends Controller
{
    public function index()
    {
        $sitemap = Sitemap::create()
            ->add(Url::create(route('frontend.home'))->setPriority(1.0)->setChangeFrequency(Url::CHANGE_FREQUENCY_ALWAYS));

        // Categories
        Category::where('is_active', true)->get()->each(function ($category) use ($sitemap) {
            $sitemap->add(Url::create(route('frontend.category.show', $category->slug))->setPriority(0.8));
        });

        // Posts
        Post::where('status', 'published')->where('published_at', '<=', now())->get()->each(function ($post) use ($sitemap) {
            $sitemap->add(Url::create(route('frontend.article.show', $post->slug))->setPriority(0.6)->setLastModificationDate($post->updated_at));
        });

        return $sitemap->toResponse(request());
    }
}
