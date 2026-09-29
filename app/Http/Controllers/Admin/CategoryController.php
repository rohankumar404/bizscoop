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
            ->withCount(['posts']);

        if ($search = $request->get('search')) {
            $query->where('name->en', 'like', "%{$search}%")
                  ->orWhere('slug', 'like', "%{$search}%");
        }

        if ($request->get('status') === 'active') {
            $query->where('is_active', true);
        } elseif ($request->get('status') === 'inactive') {
            $query->where('is_active', false);
        }

        $categories = $query->orderBy('order', 'asc')->orderBy('name', 'asc')->get();

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
        $parentCategories = Category::whereNull('parent_id')->orderBy('name')->get();

        return view('admin.categories.create', compact('parentCategories'));
    }

    // ── Store ──────────────────────────────────────────────────
    public function store(Request $request)
    {
        $request->validate([
            'name_en'           => 'required|string|max:255',
            'slug'              => 'required|string|unique:categories,slug|regex:/^[a-z0-9\-]+$/',
            'description_en'    => 'nullable|string',
            'parent_id'         => 'nullable|exists:categories,id',
            'color'             => 'nullable|string|max:20',
            'image'             => 'nullable|image|max:4096',
        ]);

        $category = Category::create([
            'parent_id'               => $request->parent_id,
            'name'                    => ['en' => $request->name_en],
            'slug'                    => $request->slug,
            'description'             => ['en' => $request->description_en],
            'is_active'               => $request->boolean('is_active'),
            'show_in_header'          => $request->boolean('show_in_header'),
            'color'                   => $request->color,
            // Defaults for simplified features
            'layout_type'             => 'grid',
            'posts_per_section'       => 6,
            'hero_priority'           => 0,
            'order'                   => 0,
        ]);

        if ($request->hasFile('image')) {
            $category->addMediaFromRequest('image')->toMediaCollection('category_image');
        }

        // SEO Meta — save provided fields + auto-generate canonical if missing
        $seoFields = $request->only(['meta_title', 'meta_description', 'meta_keywords', 'canonical_url', 'og_title', 'og_description', 'robots', 'focus_keyword']);
        if (empty($seoFields['canonical_url'])) {
            $seoFields['canonical_url'] = app(\App\Services\SeoService::class)->sectionCanonical($category->slug);
        }
        $category->seoMeta()->updateOrCreate(
            ['seoable_id' => $category->id, 'seoable_type' => Category::class],
            $seoFields
        );

        Cache::forget('global_settings');

        return redirect()->route('admin.categories.index')
            ->with('success', "Category \"{$request->name_en}\" created successfully.");
    }

    // ── Edit ───────────────────────────────────────────────────
    public function edit(Category $category)
    {
        $parentCategories = Category::whereNull('parent_id')
            ->where('id', '!=', $category->id)
            ->orderBy('name')->get();

        return view('admin.categories.edit', compact('category', 'parentCategories'));
    }

    // ── Update ─────────────────────────────────────────────────
    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name_en'           => 'required|string|max:255',
            'slug'              => 'required|string|unique:categories,slug,'.$category->id.'|regex:/^[a-z0-9\-]+$/',
            'description_en'    => 'nullable|string',
            'parent_id'         => 'nullable|exists:categories,id',
            'color'             => 'nullable|string|max:20',
            'image'             => 'nullable|image|max:4096',
        ]);

        $category->update([
            'parent_id'               => $request->parent_id,
            'name'                    => ['en' => $request->name_en],
            'slug'                    => $request->slug,
            'description'             => ['en' => $request->description_en],
            'is_active'               => $request->boolean('is_active'),
            'show_in_header'          => $request->boolean('show_in_header'),
            'color'                   => $request->color,
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

        // SEO Meta — save provided fields + auto-generate canonical if missing
        $seoFields = $request->only(['meta_title', 'meta_description', 'meta_keywords', 'canonical_url', 'og_title', 'og_description', 'robots', 'focus_keyword']);
        if (empty($seoFields['canonical_url'])) {
            $seoFields['canonical_url'] = app(\App\Services\SeoService::class)->sectionCanonical($category->slug);
        }
        $category->seoMeta()->updateOrCreate(
            ['seoable_id' => $category->id, 'seoable_type' => Category::class],
            $seoFields
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
