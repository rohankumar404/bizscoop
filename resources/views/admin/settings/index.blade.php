<x-admin-layout>
    <x-slot:section-title>System</x-slot>
    <x-slot:page-title>Global Settings</x-slot>
    
    <x-slot:page-actions>
        <form action="{{ route('admin.settings.clear-cache') }}" method="POST">
            @csrf
            <x-ui.button type="submit" variant="outline" size="sm">
                Clear Cache
            </x-ui.button>
        </form>
    </x-slot>

    <div x-data="{ activeTab: 'general' }" class="grid grid-cols-1 lg:grid-cols-12 gap-12">
        {{-- Sidebar Tabs --}}
        <div class="lg:col-span-3">
            <nav class="space-y-1 bg-white border border-[#E5E5E5] p-2">
                @foreach($settings as $group => $groupSettings)
                    <button @click="activeTab = '{{ $group }}'" 
                            :class="activeTab === '{{ $group }}' ? 'bg-black text-white' : 'text-neutral-500 hover:bg-[#F8F8F8]'"
                            class="w-full text-left px-6 py-4 text-[10px] font-bold uppercase tracking-widest transition-colors">
                        {{ $group }}
                    </button>
                @endforeach
            </nav>
        </div>

        {{-- Settings Form --}}
        <div class="lg:col-span-9">
            <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data" class="bg-white border border-[#E5E5E5] p-10">
                @csrf
                @method('PUT')

                @foreach($settings as $group => $groupSettings)
                    <div x-show="activeTab === '{{ $group }}'" class="space-y-8">
                        <h3 class="text-xs font-bold uppercase tracking-widest border-b pb-4 mb-10">{{ ucfirst($group) }} Configuration</h3>
                        
                        @foreach($groupSettings as $setting)
                            <div class="space-y-2">
                                <label class="block text-[10px] font-bold uppercase tracking-widest text-neutral-400">
                                    {{ str_replace('_', ' ', $setting->key) }}
                                </label>

                                @if($setting->type === 'text')
                                    <input type="text" name="{{ $setting->key }}" value="{{ $setting->value }}" class="w-full px-4 py-3 bg-[#F8F8F8] border-none focus:ring-1 focus:ring-black">
                                @elseif($setting->type === 'password')
                                    <input type="password" name="{{ $setting->key }}" value="{{ $setting->value }}" class="w-full px-4 py-3 bg-[#F8F8F8] border-none focus:ring-1 focus:ring-black">
                                @elseif($setting->type === 'textarea')
                                    <textarea name="{{ $setting->key }}" rows="5" class="w-full px-4 py-3 bg-[#F8F8F8] border-none font-mono text-xs focus:ring-1 focus:ring-black">{{ $setting->value }}</textarea>
                                @elseif($setting->type === 'image')
                                    <div class="flex items-center space-x-6">
                                        @if($setting->value)
                                            <div class="w-20 h-20 bg-neutral-100 border p-2">
                                                <img src="{{ Storage::url($setting->value) }}" class="w-full h-full object-contain">
                                            </div>
                                        @endif
                                        <input type="file" name="{{ $setting->key }}" class="text-xs text-neutral-500 file:mr-4 file:py-2 file:px-4 file:border-0 file:text-[10px] file:font-bold file:uppercase file:tracking-widest file:bg-black file:text-white hover:file:bg-neutral-800">
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endforeach

                <div class="mt-12 pt-8 border-t">
                    <x-ui.button type="submit" variant="primary" class="w-full">
                        Save Changes
                    </x-ui.button>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>
