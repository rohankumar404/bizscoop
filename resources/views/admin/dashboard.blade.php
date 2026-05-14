<x-admin-layout>
    <x-slot:section-title>Performance</x-slot>
    <x-slot:page-title>Platform Overview</x-slot>
    
    {{-- Welcome Header --}}
    <div class="mb-12 p-10 bg-[#0A0A0A] text-white overflow-hidden relative group rounded-sm shadow-2xl">
        <div class="relative z-10">
            <h2 class="text-4xl font-serif font-bold tracking-tight mb-2">Welcome back, {{ auth()->user()->name }}</h2>
            <p class="text-neutral-400 text-sm uppercase tracking-widest font-bold">Here is what's happening across BizScoop today.</p>
        </div>
        {{-- Animated background element --}}
        <div class="absolute -right-20 -top-20 w-64 h-64 bg-white opacity-5 rounded-full blur-3xl group-hover:scale-150 transition-transform duration-1000"></div>
        <div class="absolute right-10 bottom-10">
            <svg class="w-24 h-24 text-white opacity-5" fill="currentColor" viewBox="0 0 24 24"><path d="M13 3l-2 3H3v15h18V3h-8zm-2 16H5v-2h6v2zm0-4H5v-2h6v2zm0-4H5V9h6v2zm8 8h-6v-2h6v2zm0-4h-6v-2h6v2zm0-4h-6V9h6v2z"/></svg>
        </div>
    </div>

    {{-- Stats Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-16">
        <x-admin.stat-card title="Total Articles" :value="number_format($stats['posts'])" change="Live" trend="up" />
        <x-admin.stat-card title="Leads & Inquiries" :value="number_format($stats['leads'])" change="Active" trend="up" />
        <x-admin.stat-card title="Newsletter Subscribers" :value="number_format($stats['subscribers'])" change="Daily" trend="up" />
        <x-admin.stat-card title="Active Advertisements" :value="number_format($stats['ads'])" change="Running" trend="up" />
        <x-admin.stat-card title="Content Categories" :value="number_format($stats['categories'])" change="Global" trend="up" />
        <x-admin.stat-card title="Active Tags" :value="number_format($stats['tags'])" change="Indexed" trend="up" />
    </div>

    {{-- Two Column Layout --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
        {{-- Recent Activity --}}
        <div class="lg:col-span-2">
            <div class="bg-white border border-[#E5E5E5] h-full shadow-sm">
                <div class="px-8 py-6 border-b border-[#E5E5E5] flex justify-between items-center bg-[#F8F8F8]">
                    <h3 class="text-[10px] font-bold uppercase tracking-[0.2em] text-neutral-400">Recent Editorial Activity</h3>
                    <a href="{{ route('admin.posts.index') }}" class="text-[10px] font-bold uppercase tracking-widest text-neutral-400 hover:text-black transition-colors">View All Archive</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <tbody class="divide-y divide-[#E5E5E5] text-sm">
                            @foreach($recentPosts as $post)
                                <tr class="hover:bg-[#F8F8F8] transition-colors group">
                                    <td class="px-8 py-6">
                                        <div class="flex items-center space-x-6">
                                            <div class="w-16 h-10 flex-shrink-0 bg-neutral-100 border overflow-hidden">
                                                @if($post->hasMedia('featured_image'))
                                                    <img src="{{ $post->getFirstMediaUrl('featured_image') }}" class="w-full h-full object-cover grayscale group-hover:grayscale-0 transition-all duration-500">
                                                @else
                                                    <div class="w-full h-full flex items-center justify-center text-[8px] font-bold text-neutral-300 uppercase">No Image</div>
                                                @endif
                                            </div>
                                            <div>
                                                <p class="font-serif text-lg font-bold group-hover:text-[#e60000] transition-colors line-clamp-1">{{ $post->translate()?->title }}</p>
                                                <p class="text-[10px] text-neutral-400 uppercase tracking-widest mt-1 font-bold">
                                                    {{ $post->category?->name ?? 'Uncategorized' }} <span class="mx-2">·</span> {{ $post->published_at?->diffForHumans() ?? 'Draft' }}
                                                </p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-8 py-6 text-right">
                                        <a href="{{ route('admin.posts.edit', $post) }}" class="text-[10px] font-bold uppercase tracking-widest border-b border-black hover:border-transparent transition-all">Edit</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Quick Links & Status --}}
        <div class="space-y-8">
            <div class="bg-white border border-[#E5E5E5] p-8 shadow-sm">
                <h3 class="text-[10px] font-bold uppercase tracking-[0.2em] mb-8 border-b border-neutral-100 pb-4 text-neutral-400">Quick Management</h3>
                <div class="space-y-4">
                    <a href="{{ route('admin.posts.create') }}" class="flex items-center justify-between p-5 bg-[#F8F8F8] border border-transparent hover:border-black transition-all group">
                        <span class="text-[10px] font-bold uppercase tracking-widest">Publish New Article</span>
                        <svg class="w-4 h-4 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </a>
                    <a href="{{ route('admin.ads.create') }}" class="flex items-center justify-between p-5 bg-[#F8F8F8] border border-transparent hover:border-black transition-all group">
                        <span class="text-[10px] font-bold uppercase tracking-widest">New Advertisement</span>
                        <svg class="w-4 h-4 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </a>
                    <a href="{{ route('admin.settings.index') }}" class="flex items-center justify-between p-5 bg-[#F8F8F8] border border-transparent hover:border-black transition-all group">
                        <span class="text-[10px] font-bold uppercase tracking-widest">Global Settings</span>
                        <svg class="w-4 h-4 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </a>
                </div>
            </div>

            <div class="bg-[#0A0A0A] text-white p-8 shadow-2xl relative overflow-hidden group">
                <h3 class="text-[10px] font-bold uppercase tracking-[0.2em] mb-8 border-b border-neutral-800 pb-4 text-neutral-500">System Health</h3>
                <div class="space-y-6 relative z-10">
                    <div class="flex justify-between items-center">
                        <span class="text-[10px] font-bold uppercase tracking-widest text-neutral-400">Database Engine</span>
                        <div class="flex items-center">
                            <span class="text-[8px] mr-2 uppercase font-bold text-green-500">Operational</span>
                            <span class="w-1.5 h-1.5 bg-green-500 rounded-full animate-pulse"></span>
                        </div>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-[10px] font-bold uppercase tracking-widest text-neutral-400">Media Storage</span>
                        <div class="flex items-center">
                            <span class="text-[8px] mr-2 uppercase font-bold text-green-500">Healthy</span>
                            <span class="w-1.5 h-1.5 bg-green-500 rounded-full animate-pulse"></span>
                        </div>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-[10px] font-bold uppercase tracking-widest text-neutral-400">Mail Pipeline</span>
                        <div class="flex items-center">
                            <span class="text-[8px] mr-2 uppercase font-bold text-yellow-500">Standby</span>
                            <span class="w-1.5 h-1.5 bg-yellow-500 rounded-full"></span>
                        </div>
                    </div>
                </div>
                <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-white opacity-5 rounded-full blur-2xl group-hover:scale-110 transition-transform duration-700"></div>
            </div>
        </div>
    </div>
</x-admin-layout>
