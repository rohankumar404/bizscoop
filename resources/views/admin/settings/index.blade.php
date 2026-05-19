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
                            :class="activeTab === '{{ $group }}' ? 'bg-black text-white' : 'text-neutral-400 hover:text-black hover:bg-[#F8F8F8]'"
                            class="w-full text-left px-6 py-5 text-[10px] font-bold uppercase tracking-[0.2em] transition-all flex items-center justify-between group">
                        {{ $group }}
                        <svg x-show="activeTab === '{{ $group }}'" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
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
                    <div x-show="activeTab === '{{ $group }}'" x-cloak class="space-y-12">
                        <div class="border-b border-neutral-100 pb-8 mb-12">
                            <h3 class="text-2xl font-serif font-bold tracking-tight">{{ ucfirst($group) }} Configuration</h3>
                            <p class="text-xs text-neutral-400 uppercase tracking-widest mt-2">Manage your platform's {{ $group }} parameters and system defaults.</p>
                        </div>
                        
                        <div class="space-y-10">
                            @foreach($groupSettings as $setting)
                                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 items-start">
                                    <div class="md:col-span-1">
                                        <label class="block text-[10px] font-bold uppercase tracking-widest text-neutral-900">
                                            {{ str_replace('_', ' ', $setting->key) }}
                                        </label>
                                    </div>
                                    <div class="md:col-span-3">
                                        @if($setting->type === 'text')
                                            <input type="text" name="{{ $setting->key }}" value="{{ $setting->value }}" class="w-full px-4 py-4 bg-[#F8F8F8] border border-transparent focus:border-black focus:bg-white transition-all text-sm">
                                            @if($setting->key === 'site_logo_alt')
                                                <p class="text-[9px] text-neutral-400 uppercase tracking-wider mt-2 font-bold">SEO ALT TEXT: Crucial for search engine image indexing and screen accessibility.</p>
                                            @elseif($setting->key === 'site_footer_logo_alt')
                                                <p class="text-[9px] text-neutral-400 uppercase tracking-wider mt-2 font-bold">SEO ALT TEXT: Crucial for search engine image indexing and screen accessibility.</p>
                                            @elseif($setting->key === 'mail_mailer')
                                                <p class="text-[9px] text-neutral-400 uppercase tracking-wider mt-2 font-bold">Mailer Driver: The transport driver for outgoing emails (usually 'smtp').</p>
                                            @elseif($setting->key === 'mail_host')
                                                <p class="text-[9px] text-neutral-400 uppercase tracking-wider mt-2 font-bold">SMTP Host: The server host address of your email provider.</p>
                                            @elseif($setting->key === 'mail_port')
                                                <p class="text-[9px] text-neutral-400 uppercase tracking-wider mt-2 font-bold">SMTP Port: Connection port (e.g. 587 for TLS, 465 for SSL, or 2525).</p>
                                            @elseif($setting->key === 'mail_username')
                                                <p class="text-[9px] text-neutral-400 uppercase tracking-wider mt-2 font-bold">SMTP Username: The account credential used to authenticate connection.</p>
                                            @elseif($setting->key === 'mail_encryption')
                                                <p class="text-[9px] text-neutral-400 uppercase tracking-wider mt-2 font-bold">SMTP Encryption: Connection protocol (e.g. 'tls', 'ssl' or leave blank).</p>
                                            @elseif($setting->key === 'mail_from_address')
                                                <p class="text-[9px] text-neutral-400 uppercase tracking-wider mt-2 font-bold">Mail From Address: Outgoing sender email address (e.g. hello@bizscoop.com).</p>
                                            @elseif($setting->key === 'mail_from_name')
                                                <p class="text-[9px] text-neutral-400 uppercase tracking-wider mt-2 font-bold">Mail From Name: Display name that appears on received emails (e.g. BizScoop).</p>
                                            @endif
                                        @elseif($setting->type === 'password')
                                            <input type="password" name="{{ $setting->key }}" value="{{ $setting->value }}" class="w-full px-4 py-4 bg-[#F8F8F8] border border-transparent focus:border-black focus:bg-white transition-all text-sm">
                                            @if($setting->key === 'mail_password')
                                                <p class="text-[9px] text-neutral-400 uppercase tracking-wider mt-2 font-bold">SMTP Password: The password or secure token key corresponding to the username.</p>
                                            @endif
                                        @elseif($setting->type === 'textarea')
                                            <textarea name="{{ $setting->key }}" rows="5" class="w-full px-4 py-4 bg-[#F8F8F8] border border-transparent focus:border-black focus:bg-white transition-all font-mono text-xs">{{ $setting->value }}</textarea>
                                        @elseif($setting->type === 'image')
                                            <div class="flex items-start space-x-8">
                                                @if($setting->value)
                                                    <div class="w-32 h-16 bg-[#111111] border border-[#E5E5E5] p-2 shadow-sm flex items-center justify-center">
                                                        <img src="{{ Storage::url($setting->value) }}" class="max-w-full max-h-full object-contain">
                                                    </div>
                                                @endif
                                                <div class="flex-grow">
                                                    <input type="file" name="{{ $setting->key }}" class="text-xs text-neutral-500 file:mr-6 file:py-3 file:px-6 file:border-0 file:text-[10px] file:font-bold file:uppercase file:tracking-widest file:bg-black file:text-white hover:file:bg-neutral-800 transition-all cursor-pointer">
                                                    <p class="text-[9px] text-neutral-400 uppercase tracking-wider mt-2 font-bold">
                                                        @if($setting->key === 'site_logo')
                                                            Recommended size: 250x60 px (max height 80px). Transparent PNG or WebP format.
                                                        @elseif($setting->key === 'site_footer_logo')
                                                            Recommended size: 250x60 px. Transparent PNG/WebP (Optimized for dark background).
                                                        @elseif($setting->key === 'site_favicon')
                                                            Recommended size: 32x32 px or 16x16 px. ICO or PNG format.
                                                        @else
                                                            Recommended: WebP or PNG format.
                                                        @endif
                                                    </p>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
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
