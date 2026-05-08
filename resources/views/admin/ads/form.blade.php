<x-admin-layout>
    <x-slot:section-title>Management</x-slot>
    <x-slot:page-title>{{ isset($ad) ? 'Edit Campaign' : 'New Ad Campaign' }}</x-slot>
    
    <div class="max-w-3xl">
        <form action="{{ isset($ad) ? route('admin.ads.update', $ad) : route('admin.ads.store') }}" method="POST" enctype="multipart/form-data" class="bg-white border border-[#E5E5E5] p-10 space-y-8">
            @csrf
            @if(isset($ad)) @method('PUT') @endif

            <div class="space-y-6">
                <div>
                    <label class="block text-[10px] font-bold uppercase tracking-widest text-neutral-400 mb-2">Campaign Title</label>
                    <input type="text" name="title" value="{{ old('title', $ad->title ?? '') }}" required class="w-full px-4 py-4 bg-[#F8F8F8] border border-transparent focus:border-black focus:bg-white transition-all text-sm">
                </div>

                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-widest text-neutral-400 mb-2">Position</label>
                        <select name="position" class="w-full px-4 py-4 bg-[#F8F8F8] border border-transparent focus:border-black focus:bg-white transition-all text-xs font-bold uppercase tracking-widest">
                            <option value="sidebar" {{ (old('position', $ad->position ?? '') == 'sidebar') ? 'selected' : '' }}>Sidebar</option>
                            <option value="header" {{ (old('position', $ad->position ?? '') == 'header') ? 'selected' : '' }}>Header</option>
                            <option value="inline" {{ (old('position', $ad->position ?? '') == 'inline') ? 'selected' : '' }}>Inline Article</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-widest text-neutral-400 mb-2">Ad Type</label>
                        <select name="type" x-model="adType" class="w-full px-4 py-4 bg-[#F8F8F8] border border-transparent focus:border-black focus:bg-white transition-all text-xs font-bold uppercase tracking-widest">
                            <option value="image" {{ (old('type', $ad->type ?? 'image') == 'image') ? 'selected' : '' }}>Image Banner</option>
                            <option value="code" {{ (old('type', $ad->type ?? 'image') == 'code') ? 'selected' : '' }}>HTML / Script Code</option>
                        </select>
                    </div>
                </div>

                <div x-show="adType === 'image'" class="space-y-6">
                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-widest text-neutral-400 mb-2">Banner Image</label>
                        <input type="file" name="image" class="w-full text-xs text-neutral-500 file:mr-4 file:py-2 file:px-4 file:border-0 file:text-[10px] file:font-bold file:uppercase file:tracking-widest file:bg-black file:text-white">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-widest text-neutral-400 mb-2">Destination Link (URL)</label>
                        <input type="url" name="link" value="{{ old('link', $ad->link ?? '') }}" class="w-full px-4 py-4 bg-[#F8F8F8] border border-transparent focus:border-black focus:bg-white transition-all text-sm" placeholder="https://...">
                    </div>
                </div>

                <div x-show="adType === 'code'">
                    <label class="block text-[10px] font-bold uppercase tracking-widest text-neutral-400 mb-2">Ad Code (HTML/JS)</label>
                    <textarea name="content" rows="6" class="w-full px-4 py-4 bg-[#F8F8F8] border border-transparent focus:border-black focus:bg-white transition-all font-mono text-xs">{{ old('content', $ad->content ?? '') }}</textarea>
                </div>

                <div class="flex items-center space-x-4">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $ad->is_active ?? true) ? 'checked' : '' }} class="text-black focus:ring-black">
                    <label class="text-[10px] font-bold uppercase tracking-widest text-neutral-400">Campaign is Active</label>
                </div>
            </div>

            <div class="pt-8 border-t">
                <x-ui.button type="submit" variant="primary" class="w-full">
                    {{ isset($ad) ? 'Update Campaign' : 'Create Campaign' }}
                </x-ui.button>
            </div>
        </form>
    </div>
</x-admin-layout>
