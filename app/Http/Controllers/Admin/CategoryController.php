<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\SeoMeta;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;

class CategoryController extends Controller
{
    // ── Index ──────────────────────────────────────────────────
    public function index(Request $request)
    {
        $query = Category::with(['parent', 'media'])
            ->withCount(['posts'])
            ->orderBy('desktop_menu_order')
            ->orderBy('order');

        if ($search = $request->get('search')) {
            $query->where('name->en', 'like', "%{$search}%")
                  ->orWhere('slug', 'like', "%{$search}%");
        }

        if ($request->get('status') === 'active') {
            $query->where('is_active', true);
        } elseif ($request->get('status') === 'inactive') {
            $query->where('is_active', false);
        }

        $categories = $query->get();

        return view('admin.categories.index', compact('categories'));
    }

    // ── Trash ──────────────────────────────────────────────────
    public function trash()
    {
        $categories = Category::onlyTrashed()
            ->with('parent')
            ->latest('deleted_at')
            ->get();

        return view('admin.categories.trash', compact('categories'));
    }

    // ── Create ─────────────────────────────────────────────────
    public function create()
    {
        $parentCategories = Category::whereNull('parent_id')->orderBy('order')->get();
        $layoutTypes      = Category::LAYOUT_TYPES;

        return view('admin.categories.create', compact('parentCategories', 'layoutTypes'));
    }

    // ── Store ──────────────────────────────────────────────────
    public function store(Request $request)
    {
        $request->validate([
            'name_en'           => 'required|string|max:255',
            'slug'              => 'required|string|unique:categories,slug|regex:/^[a-z0-9\-]+$/',
            'description_en'    => 'nullable|string',
            'parent_id'         => 'nullable|exists:categories,id',
            'layout_type'       => 'required|in:grid,slider,featured,mixed',
            'posts_per_section' => 'required|integer|min:1|max:20',
            'hero_priority'     => 'required|integer|min:0|max:99',
            'color'             => 'nullable|string|max:20',
            'icon'              => 'nullable|string|max:100',
            'image'             => 'nullable|image|max:4096',
            'banner'            => 'nullable|image|max:8192',
            'category_icon_file'=> 'nullable|image|max:2048',
        ]);

        $category = Category::create([
            'parent_id'               => $request->parent_id,
            'name'                    => ['en' => $request->name_en],
            'slug'                    => $request->slug,
            'description'             => ['en' => $request->description_en],
            'order'                   => $request->order ?? 0,
            'is_active'               => $request->boolean('is_active'),
            'is_featured'             => $request->boolean('is_featured'),
            'show_in_header'          => $request->boolean('show_in_header'),
            'show_in_homepage'        => $request->boolean('show_in_homepage'),
            'show_in_hero'            => $request->boolean('show_in_hero'),
            'show_in_footer'          => $request->boolean('show_in_footer'),
            'show_in_mobile_menu'     => $request->boolean('show_in_mobile_menu'),
            'allow_sponsored_posts'   => $request->boolean('allow_sponsored_posts'),
            'enable_trending_section' => $request->boolean('enable_trending_section'),
            'enable_category_slider'  => $request->boolean('enable_category_slider'),
            'hide_from_search'        => $request->boolean('hide_from_search'),
            'premium_badge'           => $request->boolean('premium_badge'),
            'mega_menu'               => $request->boolean('mega_menu'),
            'layout_type'             => $request->layout_type,
            'posts_per_section'       => $request->posts_per_section,
            'hero_priority'           => $request->hero_priority,
            'desktop_menu_order'      => $request->desktop_menu_order ?? 0,
            'mobile_menu_order'       => $request->mobile_menu_order ?? 0,
            'color'                   => $request->color,
            'icon'                    => $request->icon,
        ]);

        // Media uploads
        if ($request->hasFile('image')) {
            $category->addMediaFromRequest('image')->toMediaCollection('category_image');
        }
        if ($request->hasFile('banner')) {
            $category->addMediaFromRequest('banner')->toMediaCollection('category_banner');
        }
        if ($request->hasFile('category_icon_file')) {
            $category->addMediaFromRequest('category_icon_file')->toMediaCollection('category_icon');
        }

        // SEO Meta
        if ($request->filled('meta_title') || $request->filled('meta_description')) {
            SeoMeta::updateOrCreate(
                ['seoable_id' => $category->id, 'seoable_type' => Category::class],
                [
                    'meta_title'       => $request->meta_title,
                    'meta_description' => $request->meta_description,
                    'meta_keywords'    => $request->meta_keywords,
                    'og_title'         => $request->og_title,
                    'og_description'   => $request->og_description,
                ]
            );
        }

        Cache::forget('global_settings');

        return redirect()->route('admin.categories.index')
            ->with('success', "Category \"{$request->name_en}\" created successfully.");
    }

    // ── Edit ───────────────────────────────────────────────────
    public function edit(Category $category)
    {
        $parentCategories = Category::whereNull('parent_id')
            ->where('id', '!=', $category->id)
            ->orderBy('order')->get();
        $category->load('seoMeta');
        $layoutTypes = Category::LAYOUT_TYPES;

        return view('admin.categories.edit', compact('category', 'parentCategories', 'layoutTypes'));
    }

    // ── Update ─────────────────────────────────────────────────
    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name_en'           => 'required|string|max:255',
            'slug'              => 'required|string|unique:categories,slug,'.$category->id.'|regex:/^[a-z0-9\-]+$/',
            'description_en'    => 'nullable|string',
            'parent_id'         => 'nullable|exists:categories,id',
            'layout_type'       => 'required|in:grid,slider,featured,mixed',
            'posts_per_section' => 'required|integer|min:1|max:20',
            'hero_priority'     => 'required|integer|min:0|max:99',
            'color'             => 'nullable|string|max:20',
            'image'             => 'nullable|image|max:4096',
            'banner'            => 'nullable|image|max:8192',
            'category_icon_file'=> 'nullable|image|max:2048',
        ]);

        $category->update([
            'parent_id'               => $request->parent_id,
            'name'                    => ['en' => $request->name_en],
            'slug'                    => $request->slug,
            'description'             => ['en' => $request->description_en],
            'order'                   => $request->order ?? $category->order,
            'is_active'               => $request->boolean('is_active'),
            'is_featured'             => $request->boolean('is_featured'),
            'show_in_header'          => $request->boolean('show_in_header'),
            'show_in_homepage'        => $request->boolean('show_in_homepage'),
            'show_in_hero'            => $request->boolean('show_in_hero'),
            'show_in_footer'          => $request->boolean('show_in_footer'),
            'show_in_mobile_menu'     => $request->boolean('show_in_mobile_menu'),
            'allow_sponsored_posts'   => $request->boolean('allow_sponsored_posts'),
            'enable_trending_section' => $request->boolean('enable_trending_section'),
            'enable_category_slider'  => $request->boolean('enable_category_slider'),
            'hide_from_search'        => $request->boolean('hide_from_search'),
            'premium_badge'           => $request->boolean('premium_badge'),
            'mega_menu'               => $request->boolean('mega_menu'),
            'layout_type'             => $request->layout_type,
            'posts_per_section'       => $request->posts_per_section,
            'hero_priority'           => $request->hero_priority,
            'desktop_menu_order'      => $request->desktop_menu_order ?? $category->desktop_menu_order,
            'mobile_menu_order'       => $request->mobile_menu_order ?? $category->mobile_menu_order,
            'color'                   => $request->color,
            'icon'                    => $request->icon,
        ]);

        if ($request->hasFile('image')) {
            $category->clearMediaCollection('category_image');
            $category->addMediaFromRequest('image')->toMediaCollection('category_image');
        }
        if ($request->hasFile('banner')) {
            $category->clearMediaCollection('category_banner');
            $category->addMediaFromRequest('banner')->toMediaCollection('category_banner');
        }
        if ($request->hasFile('category_icon_file')) {
            $category->clearMediaCollection('category_icon');
            $category->addMediaFromRequest('category_icon_file')->toMediaCollection('category_icon');
        }

        SeoMeta::updateOrCreate(
            ['seoable_id' => $category->id, 'seoable_type' => Category::class],
            [
                'meta_title'       => $request->meta_title,
                'meta_description' => $request->meta_description,
                'meta_keywords'    => $request->meta_keywords,
                'og_title'         => $request->og_title,
                'og_description'   => $request->og_description,
            ]
        );

        Cache::forget('global_settings');

        return redirect()->route('admin.categories.index')
            ->with('success', "Category \"{$request->name_en}\" updated successfully.");
    }

    // ── Soft Delete ────────────────────────────────────────────
    public function destroy(Category $category)
    {
        if ($category->posts()->count() > 0) {
            return back()->with('error', 'Cannot delete a category that has posts. Move posts first.');
        }
        $category->delete();
        Cache::forget('global_settings');

        return back()->with('success', "Category \"{$category->getTranslation('name','en')}\" moved to trash.");
    }

    // ── Restore ────────────────────────────────────────────────
    public function restore($id)
    {
        $category = Category::onlyTrashed()->findOrFail($id);
        $category->restore();
        Cache::forget('global_settings');

        return back()->with('success', "Category restored successfully.");
    }

    // ── Force Delete ───────────────────────────────────────────
    public function forceDelete($id)
    {
        $category = Category::onlyTrashed()->findOrFail($id);
        $category->clearMediaCollection('category_image');
        $category->clearMediaCollection('category_banner');
        $category->clearMediaCollection('category_icon');
        $category->forceDelete();

        return back()->with('success', 'Category permanently deleted.');
    }

    // ── Bulk Actions ───────────────────────────────────────────
    public function bulk(Request $request)
    {
        $request->validate([
            'action' => 'required|in:activate,deactivate,delete,hero_on,hero_off,header_on,header_off',
            'ids'    => 'required|array',
            'ids.*'  => 'exists:categories,id',
        ]);

        $categories = Category::whereIn('id', $request->ids)->get();

        match ($request->action) {
            'activate'    => $categories->each->update(['is_active' => true]),
            'deactivate'  => $categories->each->update(['is_active' => false]),
            'delete'      => $categories->each->delete(),
            'hero_on'     => $categories->each->update(['show_in_hero' => true]),
            'hero_off'    => $categories->each->update(['show_in_hero' => false]),
            'header_on'   => $categories->each->update(['show_in_header' => true]),
            'header_off'  => $categories->each->update(['show_in_header' => false]),
        };

        Cache::forget('global_settings');

        return back()->with('success', count($request->ids) . ' categories updated.');
    }

    // ── Update Order (drag-and-drop) ───────────────────────────
    public function updateOrder(Request $request)
    {
        $request->validate(['ids' => 'required|array']);

        foreach ($request->ids as $order => $id) {
            Category::where('id', $id)->update([
                'order'              => $order + 1,
                'desktop_menu_order' => $order + 1,
            ]);
        }

        Cache::forget('global_settings');

        return response()->json(['status' => 'success']);
    }

    // ── Quick Toggle (AJAX) ────────────────────────────────────
    public function toggle(Request $request, Category $category)
    {
        $field = $request->validate(['field' => 'required|string'])['field'];

        $allowed = [
            'is_active', 'is_featured', 'show_in_header', 'show_in_homepage',
            'show_in_hero', 'show_in_footer', 'show_in_mobile_menu',
            'allow_sponsored_posts', 'enable_trending_section',
            'enable_category_slider', 'hide_from_search',
            'premium_badge', 'mega_menu',
        ];

        if (!in_array($field, $allowed)) {
            return response()->json(['error' => 'Invalid field'], 422);
        }

        $category->update([$field => !$category->$field]);
        Cache::forget('global_settings');

        return response()->json(['value' => $category->fresh()->$field]);
    }
}
