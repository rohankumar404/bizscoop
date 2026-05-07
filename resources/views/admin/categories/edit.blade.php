<x-admin-layout>
    <x-slot:section-title>Editorial</x-slot>
    <x-slot:page-title>Edit Category: {{ $category->getTranslation('name', 'en') }}</x-slot>
    
    <form action="{{ route('admin.categories.update', $category) }}" method="POST" enctype="multipart/form-data" class="space-y-12">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
            {{-- Main Info --}}
            <div class="lg:col-span-2 space-y-8">
                <div class="bg-white border border-[#E5E5E5] p-8">
                    <h3 class="text-xs font-bold uppercase tracking-widest mb-8 border-b pb-4">Basic Information</h3>
                    
                    <div class="space-y-6">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-widest text-neutral-400 mb-2">Category Name (English)</label>
                            <input type="text" name="name[en]" value="{{ old('name.en', $category->getTranslation('name', 'en')) }}" class="w-full px-4 py-3 bg-[#F8F8F8] border-none focus:ring-1 focus:ring-black" required>
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-widest text-neutral-400 mb-2">Slug</label>
                            <input type="text" name="slug" value="{{ old('slug', $category->slug) }}" class="w-full px-4 py-3 bg-[#F8F8F8] border-none focus:ring-1 focus:ring-black" required>
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-widest text-neutral-400 mb-2">Description (English)</label>
                            <textarea name="description[en]" rows="4" class="w-full px-4 py-3 bg-[#F8F8F8] border-none focus:ring-1 focus:ring-black">{{ old('description.en', $category->getTranslation('description', 'en')) }}</textarea>
                        </div>
                    </div>
                </div>

                {{-- SEO Meta --}}
                <div class="bg-white border border-[#E5E5E5] p-8">
                    <h3 class="text-xs font-bold uppercase tracking-widest mb-8 border-b pb-4">SEO Optimization</h3>
                    
                    <div class="space-y-6">
                        @php $seo = $category->seoMeta; @endphp
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-widest text-neutral-400 mb-2">Meta Title</label>
                            <input type="text" name="meta_title" value="{{ old('meta_title', $seo->meta_title ?? '') }}" class="w-full px-4 py-3 bg-[#F8F8F8] border-none focus:ring-1 focus:ring-black">
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-widest text-neutral-400 mb-2">Meta Description</label>
                            <textarea name="meta_description" rows="2" class="w-full px-4 py-3 bg-[#F8F8F8] border-none focus:ring-1 focus:ring-black">{{ old('meta_description', $seo->meta_description ?? '') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Sidebar Settings --}}
            <div class="space-y-8">
                <div class="bg-white border border-[#E5E5E5] p-8">
                    <h3 class="text-xs font-bold uppercase tracking-widest mb-8 border-b pb-4">Visibility & Hierarchy</h3>
                    
                    <div class="space-y-6">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-widest text-neutral-400 mb-2">Parent Category</label>
                            <select name="parent_id" class="w-full px-4 py-3 bg-[#F8F8F8] border-none focus:ring-1 focus:ring-black">
                                <option value="">None (Top Level)</option>
                                @foreach($parentCategories as $parent)
                                    <option value="{{ $parent->id }}" {{ $category->parent_id == $parent->id ? 'selected' : '' }}>
                                        {{ $parent->getTranslation('name', 'en') }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="space-y-4">
                            <label class="flex items-center space-x-3 cursor-pointer group">
                                <input type="checkbox" name="is_active" value="1" {{ $category->is_active ? 'checked' : '' }} class="w-4 h-4 text-black focus:ring-black border-neutral-300">
                                <span class="text-xs font-bold uppercase tracking-widest group-hover:text-black transition-colors">Active Status</span>
                            </label>
                            
                            <label class="flex items-center space-x-3 cursor-pointer group">
                                <input type="checkbox" name="is_featured" value="1" {{ $category->is_featured ? 'checked' : '' }} class="w-4 h-4 text-black focus:ring-black border-neutral-300">
                                <span class="text-xs font-bold uppercase tracking-widest group-hover:text-black transition-colors">Featured Category</span>
                            </label>

                            <label class="flex items-center space-x-3 cursor-pointer group">
                                <input type="checkbox" name="show_in_header" value="1" {{ $category->show_in_header ? 'checked' : '' }} class="w-4 h-4 text-black focus:ring-black border-neutral-300">
                                <span class="text-xs font-bold uppercase tracking-widest group-hover:text-black transition-colors">Show in Header</span>
                            </label>

                            <label class="flex items-center space-x-3 cursor-pointer group">
                                <input type="checkbox" name="show_in_homepage" value="1" {{ $category->show_in_homepage ? 'checked' : '' }} class="w-4 h-4 text-black focus:ring-black border-neutral-300">
                                <span class="text-xs font-bold uppercase tracking-widest group-hover:text-black transition-colors">Show on Homepage</span>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="bg-white border border-[#E5E5E5] p-8">
                    <h3 class="text-xs font-bold uppercase tracking-widest mb-8 border-b pb-4">Category Image</h3>
                    <div class="space-y-4">
                        @if($category->hasMedia('category_image'))
                            <div class="mb-4 aspect-video overflow-hidden border border-[#E5E5E5]">
                                <img src="{{ $category->getFirstMediaUrl('category_image') }}" class="w-full h-full object-cover">
                            </div>
                        @endif
                        <input type="file" name="image" class="text-xs text-neutral-500 file:mr-4 file:py-2 file:px-4 file:border-0 file:text-[10px] file:font-bold file:uppercase file:tracking-widest file:bg-black file:text-white hover:file:bg-neutral-800">
                    </div>
                </div>

                <div class="pt-8">
                    <x-ui.button type="submit" variant="primary" class="w-full">
                        Update Category
                    </x-ui.button>
                </div>
            </div>
        </div>
    </form>
</x-admin-layout>
