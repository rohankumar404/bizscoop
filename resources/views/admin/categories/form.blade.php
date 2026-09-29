{{--
    Simplified Category Form Partial
    Variables: $category (nullable), $parentCategories, $formAction, $formMethod
--}}

<form action="{{ $formAction }}" method="POST" enctype="multipart/form-data" class="bg-white border border-[#E5E5E5] p-12">
    @csrf
    @if($formMethod === 'PUT') @method('PUT') @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-16">
        
        {{-- Left: Identity --}}
        <div class="space-y-10">
            <div>
                <h3 class="text-sm font-bold uppercase tracking-widest border-b border-[#E5E5E5] pb-4 mb-8">Category Identity</h3>
                
                <div class="space-y-6">
                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-widest text-neutral-400 mb-2">Category Name</label>
                        <input type="text" name="name_en" id="catName"
                               value="{{ old('name_en', $category?->getTranslation('name','en')) }}"
                               class="w-full border border-[#E5E5E5] px-4 py-3.5 text-sm focus:border-black outline-none bg-[#F8F8F8]"
                               placeholder="e.g. Technology">
                        @error('name_en')<p class="text-red-500 text-[10px] mt-1 uppercase font-bold">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-widest text-neutral-400 mb-2">URL Slug</label>
                        <input type="text" name="slug" id="catSlug"
                               value="{{ old('slug', $category?->slug) }}"
                               class="w-full border border-[#E5E5E5] px-4 py-3.5 text-sm focus:border-black outline-none bg-[#F8F8F8] font-mono"
                               placeholder="technology-news">
                        @error('slug')<p class="text-red-500 text-[10px] mt-1 uppercase font-bold">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-widest text-neutral-400 mb-2">Parent Section</label>
                        <select name="parent_id" class="w-full border border-[#E5E5E5] px-4 py-3.5 text-sm focus:border-black outline-none bg-[#F8F8F8]">
                            <option value="">— None (Top Level) —</option>
                            @foreach($parentCategories as $parent)
                                <option value="{{ $parent->id }}" {{ old('parent_id', $category?->parent_id) == $parent->id ? 'selected' : '' }}>
                                    {{ $parent->getTranslation('name','en') }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-widest text-neutral-400 mb-2">Description</label>
                        <textarea name="description_en" rows="4"
                                  class="w-full border border-[#E5E5E5] px-4 py-3.5 text-sm focus:border-black outline-none bg-[#F8F8F8] resize-none"
                                  placeholder="Briefly describe this section…">{{ old('description_en', $category?->getTranslation('description','en')) }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        {{-- Right: Visuals & Logic --}}
        <div class="space-y-10">
            <div>
                <h3 class="text-sm font-bold uppercase tracking-widest border-b border-[#E5E5E5] pb-4 mb-8">Visuals & Visibility</h3>
                
                <div class="space-y-8">
                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-widest text-neutral-400 mb-2">Section Accent Color</label>
                        <div class="flex items-center gap-4">
                            <input type="color" name="color" value="{{ old('color', $category?->color ?? '#000000') }}" class="w-12 h-12 border-0 cursor-pointer">
                            <span class="text-xs font-mono text-neutral-400 uppercase">Brand Color Tint</span>
                        </div>
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-widest text-neutral-400 mb-4">Display Logic</label>
                        <div class="space-y-4">
                            <label class="flex items-center gap-3 cursor-pointer group">
                                <input type="hidden" name="is_active" value="0">
                                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $category?->is_active ?? true) ? 'checked' : '' }} class="w-5 h-5 border-[#E5E5E5] text-black focus:ring-0">
                                <span class="text-[10px] font-bold uppercase tracking-widest text-black group-hover:text-neutral-500 transition">Publish Live</span>
                            </label>

                            <label class="flex items-center gap-3 cursor-pointer group">
                                <input type="hidden" name="show_in_header" value="0">
                                <input type="checkbox" name="show_in_header" value="1" {{ old('show_in_header', $category?->show_in_header ?? true) ? 'checked' : '' }} class="w-5 h-5 border-[#E5E5E5] text-black focus:ring-0">
                                <span class="text-[10px] font-bold uppercase tracking-widest text-black group-hover:text-neutral-500 transition">Pin to Navigation Bar</span>
                            </label>
                        </div>
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-widest text-neutral-400 mb-2">Featured Image</label>
                        @if($category && $category->getFirstMediaUrl('category_image'))
                            <div class="mb-4">
                                <img src="{{ $category->getFirstMediaUrl('category_image') }}" class="w-full h-32 object-cover border border-[#E5E5E5]">
                            </div>
                        @endif
                        <input type="file" name="image" class="text-[10px] font-bold uppercase tracking-widest text-neutral-400 file:mr-4 file:py-2 file:px-4 file:border-0 file:bg-black file:text-white hover:file:bg-neutral-800 transition-colors cursor-pointer">
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- SEO & Metadata (Phase 11) --}}
    @php
        $catSeo = $category?->seoMeta;
        $autoSectionCanon = $category ? app(\App\Services\SeoService::class)->sectionCanonical($category->slug) : 'https://bizscoopmena.com/section/[slug]';
    @endphp
    <div class="mt-16 pt-12 border-t border-[#E5E5E5]">
        <h3 class="text-sm font-bold uppercase tracking-widest border-b border-[#E5E5E5] pb-4 mb-8">Search Engine Optimization</h3>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
            <div class="space-y-6">
                <div>
                    <label class="block text-[10px] font-bold uppercase tracking-widest text-neutral-400 mb-2">
                        Meta Title <span class="text-[9px] font-normal lowercase tracking-normal text-neutral-400">(recommended 50-60 characters)</span>
                    </label>
                    <input type="text" name="meta_title" value="{{ old('meta_title', $catSeo?->meta_title) }}"
                           placeholder="Defaults to section name if left blank"
                           class="w-full border border-[#E5E5E5] px-4 py-3 text-xs focus:border-black outline-none bg-[#F8F8F8]">
                    @error('meta_title')<p class="text-red-500 text-[10px] mt-1 uppercase font-bold">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-[10px] font-bold uppercase tracking-widest text-neutral-400 mb-2">
                        Meta Description <span class="text-[9px] font-normal lowercase tracking-normal text-neutral-400">(recommended 150-160 characters)</span>
                    </label>
                    <textarea name="meta_description" rows="3"
                              placeholder="Brief synopsis for Google search snippets (150-160 characters)"
                              class="w-full border border-[#E5E5E5] px-4 py-3 text-xs focus:border-black outline-none bg-[#F8F8F8] resize-none">{{ old('meta_description', $catSeo?->meta_description) }}</textarea>
                    @error('meta_description')<p class="text-red-500 text-[10px] mt-1 uppercase font-bold">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-[10px] font-bold uppercase tracking-widest text-neutral-400 mb-2">Focus Keyword</label>
                    <input type="text" name="focus_keyword" value="{{ old('focus_keyword', $catSeo?->focus_keyword) }}"
                           placeholder="e.g. gcc business news, real estate trends"
                           class="w-full border border-[#E5E5E5] px-4 py-3 text-xs focus:border-black outline-none bg-[#F8F8F8]">
                    @error('focus_keyword')<p class="text-red-500 text-[10px] mt-1 uppercase font-bold">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="space-y-6">
                <div>
                    <label class="block text-[10px] font-bold uppercase tracking-widest text-neutral-400 mb-2">Canonical URL (Manual Override)</label>
                    <input type="url" name="canonical_url" value="{{ old('canonical_url', $catSeo?->canonical_url) }}"
                           placeholder="{{ $autoSectionCanon }}"
                           class="w-full border border-[#E5E5E5] px-4 py-3 text-xs focus:border-black outline-none bg-[#F8F8F8] font-mono">
                    <p class="text-[9px] text-neutral-400 uppercase tracking-wider mt-1">Leave empty to auto-generate: {{ $autoSectionCanon }}</p>
                    @error('canonical_url')<p class="text-red-500 text-[10px] mt-1 uppercase font-bold">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-[10px] font-bold uppercase tracking-widest text-neutral-400 mb-2">Robots Meta Directive</label>
                    <select name="robots" class="w-full border border-[#E5E5E5] px-4 py-3 text-xs focus:border-black outline-none bg-[#F8F8F8]">
                        <option value="index,follow" {{ old('robots', $catSeo?->robots ?? 'index,follow') === 'index,follow' ? 'selected' : '' }}>index, follow (Default - Allow indexing & links)</option>
                        <option value="noindex,follow" {{ old('robots', $catSeo?->robots) === 'noindex,follow' ? 'selected' : '' }}>noindex, follow (Exclude from search, follow links)</option>
                        <option value="noindex,nofollow" {{ old('robots', $catSeo?->robots) === 'noindex,nofollow' ? 'selected' : '' }}>noindex, nofollow (Block completely)</option>
                    </select>
                    @error('robots')<p class="text-red-500 text-[10px] mt-1 uppercase font-bold">{{ $message }}</p>@enderror
                </div>
            </div>
        </div>
    </div>

    <div class="mt-16 pt-8 border-t border-[#E5E5E5] flex justify-end">
        <button type="submit" class="bg-black text-white px-12 py-4 text-[10px] font-bold uppercase tracking-widest hover:bg-neutral-800 transition shadow-lg">
            {{ $category ? 'Update Section' : 'Create Section' }}
        </button>
    </div>
</form>

@push('scripts')
<script>
document.getElementById('catName').addEventListener('input', function() {
    document.getElementById('catSlug').value = this.value.toLowerCase().trim().replace(/[^\w\s-]/g, '').replace(/[\s_]+/g, '-').replace(/^-+|-+$/g, '');
});
</script>
@endpush
