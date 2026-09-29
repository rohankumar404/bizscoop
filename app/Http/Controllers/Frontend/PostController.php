<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Services\SeoService;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function show($slug, SeoService $seo)
    {
        $post = Post::where('slug', $slug)
            ->where('status', 'published')
            ->where('published_at', '<=', now())
            ->with(['author', 'category', 'tags', 'seoMeta', 'translations'])
            ->firstOrFail();

        // Increment views
        $post->increment('views');

        // Build SEO data via the central service
        $seoData = $seo->forArticle($post);

        // Related posts via SEO service (uses tag + category matching)
        $relatedPosts = $seo->relatedPosts($post);

        // Prev/Next posts
        $prevPost = Post::where('published_at', '<', $post->published_at)
            ->where('status', 'published')
            ->orderBy('published_at', 'desc')
            ->first();

        $nextPost = Post::where('published_at', '>', $post->published_at)
            ->where('status', 'published')
            ->orderBy('published_at', 'asc')
            ->first();

        return view('frontend.articles.show', compact('post', 'relatedPosts', 'prevPost', 'nextPost', 'seoData'));
    }
}
