<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function show($slug)
    {
        $post = Post::where('slug', $slug)
            ->where('status', 'published')
            ->where('published_at', '<=', now())
            ->with(['author', 'category', 'tags', 'seoMeta', 'translations'])
            ->firstOrFail();

        // Increment views (simple way for now)
        $post->increment('views');

        // Related posts (same category, excluding current)
        $relatedPosts = Post::where('category_id', $post->category_id)
            ->where('id', '!=', $post->id)
            ->where('status', 'published')
            ->limit(3)
            ->get();

        // Prev/Next posts
        $prevPost = Post::where('published_at', '<', $post->published_at)
            ->where('status', 'published')
            ->orderBy('published_at', 'desc')
            ->first();

        $nextPost = Post::where('published_at', '>', $post->published_at)
            ->where('status', 'published')
            ->orderBy('published_at', 'asc')
            ->first();

        return view('frontend.articles.show', compact('post', 'relatedPosts', 'prevPost', 'nextPost'));
    }
}
