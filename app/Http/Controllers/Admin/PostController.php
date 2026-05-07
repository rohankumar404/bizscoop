<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Category;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::with(['author', 'category'])->orderBy('published_at', 'desc')->paginate(20);
        return view('admin.posts.index', compact('posts'));
    }

    public function create()
    {
        $categories = Category::all();
        $tags = Tag::all();
        $authors = User::all(); // In real app, filter by role
        return view('admin.posts.create', compact('categories', 'tags', 'authors'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title.en' => 'required|string|max:255',
            'slug' => 'required|string|unique:posts,slug',
            'content.en' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'author_id' => 'required|exists:users,id',
            'status' => 'required|in:draft,published,scheduled',
            'type' => 'required|in:article,news,magazine',
            'published_at' => 'nullable|date',
        ]);

        $post = Post::create([
            'author_id' => $request->author_id,
            'category_id' => $request->category_id,
            'slug' => $request->slug,
            'status' => $request->status,
            'type' => $request->type,
            'published_at' => $request->published_at ?? now(),
            'reading_time' => Post::calculateReadingTime($request->input('content.en')),
            'is_sponsored' => $request->has('is_sponsored'),
            'is_trending' => $request->has('is_trending'),
            'is_featured' => $request->has('is_featured'),
        ]);

        // Translations
        foreach ($request->input('title') as $locale => $title) {
            $post->translations()->create([
                'locale' => $locale,
                'title' => $title,
                'excerpt' => $request->input("excerpt.$locale"),
                'content' => $request->input("content.$locale"),
            ]);
        }

        // Tags
        if ($request->has('tags')) {
            $post->tags()->sync($request->tags);
        }

        // Media
        if ($request->hasFile('featured_image')) {
            $post->addMediaFromRequest('featured_image')->toMediaCollection('featured_image');
        }

        if ($request->hasFile('gallery')) {
            foreach ($request->file('gallery') as $file) {
                $post->addMedia($file)->toMediaCollection('gallery');
            }
        }

        // SEO Meta
        $post->seoMeta()->create($request->only([
            'meta_title', 'meta_description', 'meta_keywords', 'canonical_url', 'og_title', 'og_description'
        ]));

        return redirect()->route('admin.posts.index')->with('success', 'Article created successfully.');
    }

    public function edit(Post $post)
    {
        $post->load(['translations', 'tags', 'seoMeta']);
        $categories = Category::all();
        $tags = Tag::all();
        $authors = User::all();
        return view('admin.posts.edit', compact('post', 'categories', 'tags', 'authors'));
    }

    public function update(Request $request, Post $post)
    {
        $validated = $request->validate([
            'title.en' => 'required|string|max:255',
            'slug' => 'required|string|unique:posts,slug,' . $post->id,
            'content.en' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'author_id' => 'required|exists:users,id',
            'status' => 'required|in:draft,published,scheduled',
            'type' => 'required|in:article,news,magazine',
        ]);

        $post->update([
            'author_id' => $request->author_id,
            'category_id' => $request->category_id,
            'slug' => $request->slug,
            'status' => $request->status,
            'type' => $request->type,
            'published_at' => $request->published_at,
            'reading_time' => Post::calculateReadingTime($request->input('content.en')),
            'is_sponsored' => $request->has('is_sponsored'),
            'is_trending' => $request->has('is_trending'),
            'is_featured' => $request->has('is_featured'),
        ]);

        // Translations
        foreach ($request->input('title') as $locale => $title) {
            $post->translations()->updateOrCreate(
                ['locale' => $locale],
                [
                    'title' => $title,
                    'excerpt' => $request->input("excerpt.$locale"),
                    'content' => $request->input("content.$locale"),
                ]
            );
        }

        // Tags
        $post->tags()->sync($request->tags ?? []);

        // Media
        if ($request->hasFile('featured_image')) {
            $post->clearMediaCollection('featured_image');
            $post->addMediaFromRequest('featured_image')->toMediaCollection('featured_image');
        }

        // SEO Meta
        $post->seoMeta()->updateOrCreate(
            ['seoable_id' => $post->id, 'seoable_type' => Post::class],
            $request->only(['meta_title', 'meta_description', 'meta_keywords', 'canonical_url', 'og_title', 'og_description'])
        );

        return redirect()->route('admin.posts.index')->with('success', 'Article updated successfully.');
    }

    public function destroy(Post $post)
    {
        $post->delete();
        return redirect()->route('admin.posts.index')->with('success', 'Article deleted successfully.');
    }
}
