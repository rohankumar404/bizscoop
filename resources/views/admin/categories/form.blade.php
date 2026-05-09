{{--
    Shared form partial for category create & edit.
    Variables expected: $category (nullable), $parentCategories, $layoutTypes
    $formAction, $formMethod
--}}

<form action="{{ $formAction }}" method="POST" enctype="multipart/form-data" id="categoryForm">
    @csrf
    @if($formMethod === 'PUT')
        @method('PUT')
    @endif

    <div class="grid grid-cols-3 gap-6">

        {{-- ════════════════════════════════════════
             LEFT COLUMN — 2/3
        ════════════════════════════════════════ --}}
        <div class="col-span-2 space-y-5">

            {{-- ── Basic Info ──────────────────────────── --}}
            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                <div class="px-5 py-3 border-b border-gray-100 bg-gray-50 flex items-center gap-2">
                    <span class="w-1 h-4 bg-red-500 rounded inline-block"></span>
                    <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wider">Basic Information</h3>
                </div>
                <div class="px-5 py-5 space-y-4">
                    {{-- Name --}}
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1.5 uppercase tracking-wide">Category Name <span class="text-red-500">*</span></label>
                        <input type="text" name="name_en" id="catName"
                               value="{{ old('name_en', $category?->getTranslation('name','en')) }}"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none @error('name_en') border-red-400 @enderror"
                               placeholder="e.g. Business, Startups, GCC News">
                        @error('name_en')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    {{-- Slug --}}
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1.5 uppercase tracking-wide">URL Slug <span class="text-red-500">*</span></label>
                        <div class="flex items-center border border-gray-300 rounded-lg overflow-hidden focus-within:ring-2 focus-within:ring-red-500 focus-within:border-red-500">
                            <span class="px-3 py-2.5 bg-gray-50 text-xs text-gray-400 border-r border-gray-300 font-mono">/</span>
                            <input type="text" name="slug" id="catSlug"
                                   value="{{ old('slug', $category?->slug) }}"
                                   class="flex-1 px-3 py-2.5 text-sm outline-none font-mono @error('slug') border-red-400 @enderror"
                                   placeholder="business-news">
                            <button type="button" onclick="regenerateSlug()"
                                    class="px-3 py-2.5 text-xs text-red-600 font-bold border-l border-gray-300 hover:bg-red-50 transition">
                                Auto
                            </button>
                        </div>
                        @error('slug')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        <p class="text-xs text-gray-400 mt-1">Only lowercase letters, numbers, and hyphens.</p>
                    </div>

                    {{-- Description --}}
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1.5 uppercase tracking-wide">Short Description</label>
                        <textarea name="description_en" rows="3"
                                  class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none resize-none"
                                  placeholder="Brief summary shown on category pages…">{{ old('description_en', $category?->getTranslation('description','en')) }}</textarea>
                    </div>

                    {{-- Parent + Layout --}}
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1.5 uppercase tracking-wide">Parent Category</label>
                            <select name="parent_id" class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none">
                                <option value="">— None (top-level) —</option>
                                @foreach($parentCategories as $parent)
                                    <option value="{{ $parent->id }}"
                                            {{ old('parent_id', $category?->parent_id) == $parent->id ? 'selected' : '' }}>
                                        {{ $parent->getTranslation('name','en') }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1.5 uppercase tracking-wide">Homepage Layout</label>
                            <select name="layout_type" class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none">
                                @foreach($layoutTypes as $value => $label)
                                    <option value="{{ $value }}"
                                            {{ old('layout_type', $category?->layout_type ?? 'grid') === $value ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Posts per section + Order + Hero Priority --}}
                    <div class="grid grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1.5 uppercase tracking-wide">Posts Per Section</label>
                            <input type="number" name="posts_per_section" min="1" max="20"
                                   value="{{ old('posts_per_section', $category?->posts_per_section ?? 6) }}"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-red-500 outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1.5 uppercase tracking-wide">Desktop Menu Order</label>
                            <input type="number" name="desktop_menu_order" min="0"
                                   value="{{ old('desktop_menu_order', $category?->desktop_menu_order ?? 0) }}"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-red-500 outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1.5 uppercase tracking-wide">Hero Priority</label>
                            <input type="number" name="hero_priority" min="0" max="99"
                                   value="{{ old('hero_priority', $category?->hero_priority ?? 0) }}"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-red-500 outline-none">
                            <p class="text-xs text-gray-400 mt-1">Lower = higher priority in hero</p>
                        </div>
                    </div>

                    {{-- Accent Color + Icon class --}}
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1.5 uppercase tracking-wide">Accent Color</label>
                            <div class="flex items-center gap-2">
                                <input type="color" name="color" id="colorPicker"
                                       value="{{ old('color', $category?->color ?? '#e60000') }}"
                                       class="w-10 h-10 border border-gray-300 rounded-lg cursor-pointer p-0.5">
                                <input type="text" id="colorText"
                                       value="{{ old('color', $category?->color ?? '#e60000') }}"
                                       class="flex-1 border border-gray-300 rounded-lg px-3 py-2.5 text-sm font-mono focus:ring-2 focus:ring-red-500 outline-none"
                                       placeholder="#e60000" oninput="document.getElementById('colorPicker').value=this.value">
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1.5 uppercase tracking-wide">Icon Class (optional)</label>
                            <input type="text" name="icon"
                                   value="{{ old('icon', $category?->icon) }}"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-red-500 outline-none"
                                   placeholder="fa fa-building">
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── SEO / Open Graph ────────────────────── --}}
            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                <div class="px-5 py-3 border-b border-gray-100 bg-gray-50 flex items-center gap-2">
                    <span class="w-1 h-4 bg-blue-500 rounded inline-block"></span>
                    <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wider">SEO & Open Graph</h3>
                </div>
                <div class="px-5 py-5 space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1.5 uppercase tracking-wide">Meta Title</label>
                            <input type="text" name="meta_title"
                                   value="{{ old('meta_title', $category?->seoMeta?->meta_title) }}"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-red-500 outline-none"
                                   placeholder="SEO title tag">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1.5 uppercase tracking-wide">OG Title</label>
                            <input type="text" name="og_title"
                                   value="{{ old('og_title', $category?->seoMeta?->og_title) }}"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-red-500 outline-none"
                                   placeholder="Open Graph title">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1.5 uppercase tracking-wide">Meta Description</label>
                        <textarea name="meta_description" rows="2"
                                  class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-red-500 outline-none resize-none"
                                  placeholder="155-character meta description…">{{ old('meta_description', $category?->seoMeta?->meta_description) }}</textarea>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1.5 uppercase tracking-wide">OG Description</label>
                        <textarea name="og_description" rows="2"
                                  class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-red-500 outline-none resize-none"
                                  placeholder="Social media description…">{{ old('og_description', $category?->seoMeta?->og_description) }}</textarea>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1.5 uppercase tracking-wide">Keywords</label>
                        <input type="text" name="meta_keywords"
                               value="{{ old('meta_keywords', $category?->seoMeta?->meta_keywords) }}"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-red-500 outline-none"
                               placeholder="business, startups, mena, economy">
                    </div>
                </div>
            </div>

            {{-- ── Image Uploads ───────────────────────── --}}
            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                <div class="px-5 py-3 border-b border-gray-100 bg-gray-50 flex items-center gap-2">
                    <span class="w-1 h-4 bg-green-500 rounded inline-block"></span>
                    <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wider">Media & Images</h3>
                </div>
                <div class="px-5 py-5 grid grid-cols-3 gap-5">
                    @foreach([
                        ['image',              'Featured Image',   '1:1 or 16:9', 'category_image'],
                        ['banner',             'Category Banner',  'Wide 16:5',   'category_banner'],
                        ['category_icon_file', 'Icon / Logo',      'Square 1:1',  'category_icon'],
                    ] as [$fieldName, $label, $hint, $collection])
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-2 uppercase tracking-wide">{{ $label }}</label>
                        @if($category && $category->getFirstMediaUrl($collection))
                            <div class="mb-2 relative group">
                                <img src="{{ $category->getFirstMediaUrl($collection) }}"
                                     class="w-full h-28 object-cover rounded-lg border border-gray-200">
                                <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 rounded-lg transition"></div>
                            </div>
                        @endif
                        <label class="flex flex-col items-center justify-center h-28 border-2 border-dashed border-gray-300 rounded-lg cursor-pointer hover:border-red-400 hover:bg-red-50 transition">
                            <svg class="w-6 h-6 text-gray-400 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                            <span class="text-xs text-gray-500">Upload {{ $label }}</span>
                            <span class="text-xs text-gray-400 mt-0.5">{{ $hint }}</span>
                            <input type="file" name="{{ $fieldName }}" class="hidden" accept="image/*">
                        </label>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- ════════════════════════════════════════
             RIGHT COLUMN — 1/3
        ════════════════════════════════════════ --}}
        <div class="col-span-1 space-y-5">

            {{-- ── Publish / Status ────────────────────── --}}
            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                <div class="px-5 py-3 border-b border-gray-100 bg-gray-50 flex items-center gap-2">
                    <span class="w-1 h-4 bg-gray-700 rounded inline-block"></span>
                    <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wider">Status</h3>
                </div>
                <div class="px-5 py-4 space-y-3">
                    @foreach([
                        ['is_active',   'Active', 'Category is live and visible'],
                        ['is_featured', 'Featured', 'Highlight as a featured category'],
                    ] as [$field, $label, $desc])
                    <label class="flex items-center justify-between cursor-pointer py-1">
                        <div>
                            <p class="text-sm font-semibold text-gray-800">{{ $label }}</p>
                            <p class="text-xs text-gray-400">{{ $desc }}</p>
                        </div>
                        <input type="hidden" name="{{ $field }}" value="0">
                        <input type="checkbox" name="{{ $field }}" value="1"
                               {{ old($field, $category?->$field ?? ($field === 'is_active' ? true : false)) ? 'checked' : '' }}
                               class="w-5 h-5 rounded border-gray-300 text-red-600 focus:ring-red-500">
                    </label>
                    @endforeach
                </div>
                <div class="px-5 pb-4">
                    <button type="submit"
                            class="w-full py-2.5 bg-red-600 hover:bg-red-700 text-white font-bold text-sm rounded-lg transition shadow-sm">
                        {{ $category ? 'Save Changes' : 'Create Category' }}
                    </button>
                </div>
            </div>

            {{-- ── Display Options ─────────────────────── --}}
            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                <div class="px-5 py-3 border-b border-gray-100 bg-gray-50 flex items-center gap-2">
                    <span class="w-1 h-4 bg-red-500 rounded inline-block"></span>
                    <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wider">Display Options</h3>
                </div>
                <div class="px-5 py-4 space-y-3">
                    @foreach([
                        ['show_in_header',      '📌 Show in Header Menu',    'Appears in main navigation'],
                        ['show_in_homepage',    '🏠 Show on Homepage',       'Category section on home page'],
                        ['show_in_hero',        '⭐ Show in Hero Section',   'Appears in the hero slider'],
                        ['show_in_footer',      '📋 Show in Footer',         'Footer links section'],
                        ['show_in_mobile_menu', '📱 Show in Mobile Menu',    'Mobile navigation drawer'],
                        ['mega_menu',           '📂 Enable Mega Menu',       'Dropdown with sub-categories'],
                    ] as [$field, $label, $desc])
                    <label class="flex items-center justify-between cursor-pointer py-1 border-b border-gray-50 last:border-0">
                        <div>
                            <p class="text-xs font-semibold text-gray-700">{{ $label }}</p>
                            <p class="text-xs text-gray-400">{{ $desc }}</p>
                        </div>
                        <input type="hidden" name="{{ $field }}" value="0">
                        <input type="checkbox" name="{{ $field }}" value="1"
                               {{ old($field, $category?->$field) ? 'checked' : '' }}
                               class="w-4 h-4 rounded border-gray-300 text-red-600 focus:ring-red-500">
                    </label>
                    @endforeach
                </div>
            </div>

            {{-- ── Content Settings ────────────────────── --}}
            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                <div class="px-5 py-3 border-b border-gray-100 bg-gray-50 flex items-center gap-2">
                    <span class="w-1 h-4 bg-amber-500 rounded inline-block"></span>
                    <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wider">Content Settings</h3>
                </div>
                <div class="px-5 py-4 space-y-3">
                    @foreach([
                        ['allow_sponsored_posts',   '💰 Allow Sponsored Posts',   'Accept paid content'],
                        ['enable_trending_section', '🔥 Enable Trending Section', 'Show trending sidebar'],
                        ['enable_category_slider',  '🎠 Enable Slider Mode',      'Posts displayed as carousel'],
                        ['hide_from_search',        '🔍 Hide from Search',        'Exclude from site search'],
                        ['premium_badge',           '⭐ Premium Category Badge',  'Show premium label'],
                    ] as [$field, $label, $desc])
                    <label class="flex items-center justify-between cursor-pointer py-1 border-b border-gray-50 last:border-0">
                        <div>
                            <p class="text-xs font-semibold text-gray-700">{{ $label }}</p>
                            <p class="text-xs text-gray-400">{{ $desc }}</p>
                        </div>
                        <input type="hidden" name="{{ $field }}" value="0">
                        <input type="checkbox" name="{{ $field }}" value="1"
                               {{ old($field, $category?->$field) ? 'checked' : '' }}
                               class="w-4 h-4 rounded border-gray-300 text-red-600 focus:ring-red-500">
                    </label>
                    @endforeach
                </div>
            </div>

        </div>{{-- /right col --}}
    </div>{{-- /grid --}}
</form>

@push('scripts')
<script>
// Auto-generate slug from name
document.getElementById('catName').addEventListener('input', function() {
    document.getElementById('catSlug').value = slugify(this.value);
});
document.getElementById('colorPicker').addEventListener('input', function() {
    document.getElementById('colorText').value = this.value;
});
function regenerateSlug() {
    const name = document.getElementById('catName').value;
    document.getElementById('catSlug').value = slugify(name);
}
function slugify(str) {
    return str.toLowerCase().trim()
        .replace(/[^\w\s-]/g, '')
        .replace(/[\s_]+/g, '-')
        .replace(/^-+|-+$/g, '');
}
</script>
@endpush
