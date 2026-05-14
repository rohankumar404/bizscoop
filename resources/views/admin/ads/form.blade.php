<x-admin-layout>
    <x-slot:section-title>Management</x-slot>
    <x-slot:page-title>{{ $ad->exists ? 'Edit Campaign' : 'New Ad Campaign' }}</x-slot>
    
    <div class="max-w-3xl">
        <form action="{{ $ad->exists ? route('admin.ads.update', $ad) : route('admin.ads.store') }}" method="POST" enctype="multipart/form-data" class="bg-white border border-[#E5E5E5] p-10 space-y-8">
            @csrf
            @if($ad->exists) @method('PUT') @endif

            <div class="space-y-6" x-data="{ adType: '{{ old('type', $ad->type ?? 'image') }}' }">
                <div>
                    <label class="block text-[10px] font-bold uppercase tracking-widest text-neutral-400 mb-2">Campaign Title</label>
                    <input type="text" name="title" value="{{ old('title', $ad->title ?? '') }}" required class="w-full px-4 py-4 bg-[#F8F8F8] border border-transparent focus:border-black focus:bg-white transition-all text-sm">
                </div>

                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-widest text-neutral-400 mb-2">Ad Type</label>
                        <select name="type" x-model="adType" class="w-full px-4 py-4 bg-[#F8F8F8] border border-transparent focus:border-black focus:bg-white transition-all text-xs font-bold uppercase tracking-widest">
                            <option value="image" {{ (old('type', $ad->type ?? 'image') == 'image') ? 'selected' : '' }}>Image Banner</option>
                            <option value="code" {{ (old('type', $ad->type ?? '') == 'code') ? 'selected' : '' }}>HTML / Script Code</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-widest text-neutral-400 mb-2">Placement (Hold Ctrl/Cmd to select multiple)</label>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 bg-[#F8F8F8] border border-[#E5E5E5] p-6 max-h-[300px] overflow-y-auto">
                            @php
                                $placements = [
                                    'header' => 'Header Box (728 x 90px)',
                                    'home_between_1' => 'Home Page - Mid Section 1 (1140 x 150px)',
                                    'home_between_2' => 'Home Page - Mid Section 2 (1140 x 150px)',
                                    'home_sidebar' => 'Home Page - Sidebar Top (300 x 250px)',
                                    'home_sidebar_2' => 'Home Page - Sidebar Bottom (300 x 250px)',
                                    'category_sidebar' => 'Category Page - Sidebar (300 x 250px)',
                                    'search_sidebar' => 'Search Results - Sidebar (300 x 250px)',
                                    'article_sidebar' => 'Article Page - Sidebar (300 x 250px)',
                                    'article_bottom' => 'Article Page - Bottom (728 x 90px)',
                                ];
                            @endphp
                            @foreach($placements as $val => $label)
                                <label class="flex items-center space-x-3 cursor-pointer group">
                                    <input type="checkbox" name="position[]" value="{{ $val }}" 
                                           {{ is_array(old('position', $ad->position ?? [])) && in_array($val, old('position', $ad->position ?? [])) ? 'checked' : '' }}
                                           class="w-4 h-4 text-black border-gray-300 focus:ring-black rounded-sm transition-all">
                                    <span class="text-[11px] font-bold uppercase tracking-tight text-neutral-600 group-hover:text-black transition-colors">{{ $label }}</span>
                                </label>
                            @endforeach
                        </div>
                        <p class="text-[10px] text-neutral-400 mt-2 uppercase tracking-widest font-bold">Select all places where this advertisement should appear</p>
                    </div>
                </div>

                <div x-show="adType === 'image'" class="space-y-6">
                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-widest text-neutral-400 mb-2">Banner Image</label>
                        <input type="file" name="image" class="w-full text-xs text-neutral-500 file:mr-4 file:py-2 file:px-4 file:border-0 file:text-[10px] file:font-bold file:uppercase file:tracking-widest file:bg-black file:text-white">
                        @if($ad->image)
                            <div class="mt-2 text-xs text-neutral-500">Current: {{ basename($ad->image) }}</div>
                        @endif
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-widest text-neutral-400 mb-2">Text Overlay (Optional)</label>
                        <input type="text" name="content" value="{{ old('content', $ad->content ?? '') }}" class="w-full px-4 py-4 bg-[#F8F8F8] border border-transparent focus:border-black focus:bg-white transition-all text-sm" placeholder="Text to show on the banner box">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-widest text-neutral-400 mb-2">Destination Link (URL)</label>
                        <input type="url" name="link" value="{{ old('link', $ad->link ?? '') }}" class="w-full px-4 py-4 bg-[#F8F8F8] border border-transparent focus:border-black focus:bg-white transition-all text-sm" placeholder="https://...">
                    </div>
                </div>

                <div x-show="adType === 'code'">
                    <label class="block text-[10px] font-bold uppercase tracking-widest text-neutral-400 mb-2">Ad Code (HTML/JS)</label>
                    <textarea name="content" rows="6" class="w-full px-4 py-4 bg-[#F8F8F8] border border-transparent focus:border-black focus:bg-white transition-all font-mono text-xs" :disabled="adType !== 'code'">{{ old('content', $ad->content ?? '') }}</textarea>
                </div>

                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-widest text-neutral-400 mb-2">Start Date & Time (Optional)</label>
                        <input type="datetime-local" name="starts_at" value="{{ old('starts_at', isset($ad->starts_at) ? $ad->starts_at->format('Y-m-d\TH:i') : '') }}" class="w-full px-4 py-4 bg-[#F8F8F8] border border-transparent focus:border-black focus:bg-white transition-all text-sm">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-widest text-neutral-400 mb-2">End Date & Time (Optional)</label>
                        <input type="datetime-local" name="expires_at" value="{{ old('expires_at', isset($ad->expires_at) ? $ad->expires_at->format('Y-m-d\TH:i') : '') }}" class="w-full px-4 py-4 bg-[#F8F8F8] border border-transparent focus:border-black focus:bg-white transition-all text-sm">
                    </div>
                </div>

                {{-- Status Toggle --}}
                <div class="flex items-center justify-between p-5 bg-[#F8F8F8] border border-[#E5E5E5] rounded-sm">
                    <div x-data="{ active: {{ old('is_active', $ad->exists ? $ad->is_active : true) ? 'true' : 'false' }} }">
                        <h4 class="text-[10px] font-bold uppercase tracking-widest" :class="active ? 'text-black' : 'text-neutral-400'">
                            Campaign Status: <span x-text="active ? 'Published' : 'Draft / Paused'"></span>
                        </h4>
                        <p class="text-[10px] text-neutral-400 mt-1 uppercase tracking-widest">
                            Only 'Published' ads appear on the site (subject to scheduling)
                        </p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" class="sr-only peer" 
                               x-on:change="active = $el.checked"
                               {{ old('is_active', $ad->exists ? $ad->is_active : true) ? 'checked' : '' }}>
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#e60000]"></div>
                    </label>
                </div>
            </div>

            <div class="pt-8 border-t">
                <x-ui.button type="submit" variant="primary" class="w-full py-4 text-[10px] font-bold uppercase tracking-widest">
                    {{ $ad->exists ? 'Update Campaign' : 'Launch Campaign' }}
                </x-ui.button>
            </div>
        </form>
    </div>
</x-admin-layout>
