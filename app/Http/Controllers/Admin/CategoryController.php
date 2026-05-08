<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::with('parent')->orderBy('order')->get();
        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        $parentCategories = Category::whereNull('parent_id')->get();
        return view('admin.categories.create', compact('parentCategories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name.en' => 'required|string|max:255',
            'slug' => 'required|string|unique:categories,slug',
            'description.en' => 'nullable|string',
            'parent_id' => 'nullable|exists:categories,id',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'show_in_header' => 'boolean',
            'show_in_homepage' => 'boolean',
        ]);

        $category = Category::create($request->all());

        if ($request->hasFile('image')) {
            $category->addMediaFromRequest('image')->toMediaCollection('category_image');
        }

        // SEO Meta
        $category->seoMeta()->updateOrCreate(
            ['seoable_id' => $category->id, 'seoable_type' => Category::class],
            $request->only(['meta_title', 'meta_description', 'meta_keywords', 'canonical_url', 'og_title', 'og_description'])
        );

        return redirect()->route('admin.categories.index')->with('success', 'Category created successfully.');
    }

    public function edit(Category $category)
    {
        $parentCategories = Category::whereNull('parent_id')->where('id', '!=', $category->id)->get();
        $category->load('seoMeta');
        return view('admin.categories.edit', compact('category', 'parentCategories'));
    }

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name.en' => 'required|string|max:255',
            'slug' => 'required|string|unique:categories,slug,' . $category->id,
            'description.en' => 'nullable|string',
            'parent_id' => 'nullable|exists:categories,id',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'show_in_header' => 'boolean',
            'show_in_homepage' => 'boolean',
        ]);

        $category->update($request->all());

        if ($request->hasFile('image')) {
            $category->clearMediaCollection('category_image');
            $category->addMediaFromRequest('image')->toMediaCollection('category_image');
        }

        // SEO Meta update
        $category->seoMeta()->updateOrCreate(
            ['seoable_id' => $category->id, 'seoable_type' => Category::class],
            $request->only(['meta_title', 'meta_description', 'meta_keywords', 'canonical_url', 'og_title', 'og_description'])
        );

        return redirect()->route('admin.categories.index')->with('success', 'Category updated successfully.');
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return redirect()->route('admin.categories.index')->with('success', 'Category deleted successfully.');
    }

    public function updateOrder(Request $request)
    {
        Category::setNewOrder($request->ids);
        return response()->json(['status' => 'success']);
    }
}
