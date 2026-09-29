<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Post;
use App\Services\SeoService;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function show($slug, SeoService $seo)
    {
        $category = Category::where('slug', $slug)
            ->where('is_active', true)
            ->with(['seoMeta', 'children'])
            ->firstOrFail();

        $categoryIds = array_merge(
            [$category->id],
            $category->children->where('is_active', true)->pluck('id')->toArray()
        );

        $posts = Post::whereIn('category_id', $categoryIds)
            ->where('status', 'published')
            ->where('published_at', '<=', now())
            ->orderBy('published_at', 'desc')
            ->paginate(12);

        // Build SEO data via the central service
        $seoData = $seo->forSection($category);

        return view('frontend.categories.show', compact('category', 'posts', 'seoData'));
    }
}
