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
