<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Category;
use App\Models\Magazine;
use App\Services\SeoService;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request, SeoService $seo)
    {
        $query = $request->input('q');

        if (empty($query)) {
            $seoData = $seo->forUtilityPage('search', 'Search | Bizscoop');
            return view('frontend.search.index', [
                'results' => collect(),
                'query'   => '',
                'seoData' => $seoData,
            ]);
        }

        $posts = Post::where('status', 'published')
            ->whereHas('translations', function ($q) use ($query) {
                $q->where('title', 'LIKE', "%{$query}%")
                  ->orWhere('content', 'LIKE', "%{$query}%");
            })
            ->with(['translations', 'category', 'author'])
            ->latest('published_at')
            ->paginate(15);

        $seoData = $seo->forUtilityPage('search', "Results for \"{$query}\" | Bizscoop");

        return view('frontend.search.index', [
            'results' => $posts,
            'query'   => $query,
            'seoData' => $seoData,
        ]);
    }

    public function live(Request $request)
    {
        $query = $request->input('q');

        if (strlen($query) < 2) {
            return response()->json(['results' => []]);
        }

        $posts = Post::where('status', 'published')
            ->whereHas('translations', function ($q) use ($query) {
                $q->where('title', 'LIKE', "%{$query}%");
            })
            ->limit(5)
            ->get()
            ->map(function ($post) {
                return [
                    'title' => $post->translate()->title,
                    'url' => route('frontend.article.show', $post->slug),
                    'type' => 'Article',
                    'category' => $post->category->getTranslation('name', app()->getLocale())
                ];
            });

        $categories = Category::where('is_active', true)
            ->where('name', 'LIKE', "%{$query}%")
            ->limit(3)
            ->get()
            ->map(function ($cat) {
                return [
                    'title' => $cat->getTranslation('name', app()->getLocale()),
                    'url' => route('frontend.category.show', $cat->slug),
                    'type' => 'Section'
                ];
            });

        return response()->json([
            'results' => $posts->concat($categories)
        ]);
    }
}
