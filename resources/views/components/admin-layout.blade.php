<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin - {{ config('app.name', 'Bizscoop') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    {{-- Fail-safe for local development --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    @stack('styles')
</head>
<body class="bg-[#F8F8F8] text-[#0A0A0A] antialiased font-sans" x-data="{ sidebarOpen: false, searchOpen: false }">
    <div class="min-h-screen flex">
        {{-- Sidebar --}}
        <aside class="fixed inset-y-0 left-0 z-50 w-72 bg-[#0A0A0A] text-white transform transition-transform duration-300 lg:relative lg:translate-x-0"
               :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">
            <div class="h-full flex flex-col p-8">
                <div class="flex items-center justify-between mb-12">
                    <a href="{{ route('admin.dashboard') }}" class="font-serif text-3xl font-bold tracking-tighter italic">
                        Bizscoop<span class="text-neutral-500 font-sans text-xs align-top ml-1 uppercase tracking-widest not-italic">Admin</span>
                    </a>
                    <button @click="sidebarOpen = false" class="lg:hidden text-neutral-500 hover:text-white transition-colors">&times;</button>
                </div>
                
                <nav class="flex-grow space-y-6">
                    <div>
                        <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-neutral-600 mb-4">Quick Actions</p>
                        <div class="space-y-1">
                            <x-admin.nav-link href="{{ route('admin.posts.create') }}" :active="request()->routeIs('admin.posts.create')">
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                    Publish New Article
                                </span>
                            </x-admin.nav-link>
                            <x-admin.nav-link href="{{ route('admin.categories.create') }}" :active="request()->routeIs('admin.categories.create')">
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path></svg>
                                    Create Category
                                </span>
                            </x-admin.nav-link>
                            <x-admin.nav-link href="{{ route('admin.ads.create') }}" :active="request()->routeIs('admin.ads.create')">
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    New Advertisement
                                </span>
                            </x-admin.nav-link>
                        </div>
                    </div>

                    <div>
                        <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-neutral-600 mb-4">Core</p>
                        <div class="space-y-1">
                            <x-admin.nav-link href="{{ route('admin.dashboard') }}" :active="request()->routeIs('admin.dashboard')">
                                Dashboard Overview
                            </x-admin.nav-link>
                        </div>
                    </div>

                    <div>
                        <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-neutral-600 mb-4">Editorial Desk</p>
                        <div class="space-y-1">
                            <x-admin.nav-link href="{{ route('admin.posts.index') }}" :active="request()->routeIs('admin.posts.index') && !request('type')">All Articles</x-admin.nav-link>
                            <x-admin.nav-link href="{{ route('admin.posts.index', ['type' => 'news']) }}" :active="request('type') == 'news'">Breaking News</x-admin.nav-link>
                            <x-admin.nav-link href="{{ route('admin.magazines.index') }}" :active="request()->routeIs('admin.magazines.*')">Digital Magazines</x-admin.nav-link>
                            <x-admin.nav-link href="{{ route('admin.videos.index') }}" :active="request()->routeIs('admin.videos.*')">Video Gallery</x-admin.nav-link>
                            <x-admin.nav-link href="{{ route('admin.categories.index') }}" :active="request()->routeIs('admin.categories.*')">Categories</x-admin.nav-link>
                            <x-admin.nav-link href="{{ route('admin.tags.index') }}" :active="request()->routeIs('admin.tags.*')">Tags</x-admin.nav-link>
                        </div>
                    </div>

                    <div>
                        <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-neutral-600 mb-4">Management</p>
                        <div class="space-y-1">
                            <x-admin.nav-link href="{{ route('admin.settings.index') }}" :active="request()->routeIs('admin.settings.*')">
                                Global Settings
                            </x-admin.nav-link>
                            <x-admin.nav-link href="{{ route('admin.newsletters.index') }}" :active="request()->routeIs('admin.newsletters.*')">Newsletters</x-admin.nav-link>
                            <x-admin.nav-link href="{{ route('admin.leads.index') }}" :active="request()->routeIs('admin.leads.*')">Leads & Inquiries</x-admin.nav-link>
                            <x-admin.nav-link href="{{ route('admin.jobs.index') }}" :active="request()->routeIs('admin.jobs.*')">Job Postings</x-admin.nav-link>
                            <x-admin.nav-link href="{{ route('admin.polls.index') }}" :active="request()->routeIs('admin.polls.*')">Reader Polls</x-admin.nav-link>
                            <x-admin.nav-link href="{{ route('admin.ads.index') }}" :active="request()->routeIs('admin.ads.*')">Ads & Sponsors</x-admin.nav-link>
                        </div>
                    </div>
                </nav>

                <div class="mt-auto pt-8 border-t border-neutral-900">
                    <div class="flex items-center space-x-4">
                        <div class="w-10 h-10 bg-neutral-900 border border-neutral-800 rounded-lg flex items-center justify-center font-bold text-neutral-400">
                            {{ substr(auth()->user()->name ?? 'A', 0, 1) }}
                        </div>
                        <div class="min-w-0">
                            <p class="text-sm font-bold truncate">{{ auth()->user()->name ?? 'Administrator' }}</p>
                            <p class="text-[10px] text-neutral-500 uppercase tracking-widest">Editor-in-Chief</p>
                        </div>
                    </div>
                </div>
            </div>
        </aside>

        {{-- Main Area --}}
        <div class="flex-1 flex flex-col min-w-0 bg-[#F8F8F8]">
            {{-- Header --}}
            <header class="bg-white border-b border-[#E5E5E5] h-20 flex items-center px-8 sticky top-0 z-40">
                <button @click="sidebarOpen = true" class="lg:hidden mr-6 text-neutral-400 hover:text-black">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                </button>

                {{-- Search Bar --}}
                <div class="relative max-w-md w-full hidden md:block">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-neutral-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </span>
                    <input type="text" placeholder="Search across Bizscoop..." class="w-full pl-10 pr-4 py-2 bg-[#F8F8F8] border-none rounded-none text-sm focus:ring-1 focus:ring-black transition-all">
                </div>
                
                <div class="ml-auto flex items-center space-x-6">
                    {{-- Notifications --}}
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="text-neutral-400 hover:text-black relative">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                            <span class="absolute top-0 right-0 w-2 h-2 bg-red-600 rounded-full"></span>
                        </button>
                        
                        <div x-show="open" @click.away="open = false" class="absolute right-0 mt-4 w-80 bg-white border border-[#E5E5E5] shadow-xl p-4">
                            <h3 class="text-xs font-bold uppercase tracking-widest mb-4 border-b pb-2">Recent Notifications</h3>
                            <div class="space-y-4">
                                <div class="flex space-x-3 p-2 hover:bg-[#F8F8F8] transition-colors cursor-pointer">
                                    <div class="w-2 h-2 bg-black rounded-full mt-1.5"></div>
                                    <div class="text-xs">
                                        <p class="font-bold">New article pending review</p>
                                        <p class="text-neutral-500 mt-1">"The Future of AI Governance" was submitted.</p>
                                    </div>
                                </div>
                            </div>
                            <button class="w-full mt-4 py-2 text-[10px] font-bold uppercase tracking-widest text-neutral-400 hover:text-black transition-colors">View All Activity</button>
                        </div>
                    </div>

                    <div class="flex items-center space-x-6">
                        <a href="{{ route('admin.profile.edit') }}" class="text-xs font-bold uppercase tracking-widest text-neutral-400 hover:text-black transition-all">Profile Settings</a>
                        <a href="{{ route('frontend.home') }}" target="_blank" class="text-xs font-bold uppercase tracking-widest border-b border-black">View Site</a>
                        
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="text-xs font-bold uppercase tracking-widest text-red-600 hover:text-red-700 transition-colors">Logout</button>
                        </form>
                    </div>
                </div>
            </header>

            {{-- Page Content --}}
            <main class="p-8 lg:p-12 flex-grow">
                <div class="max-w-7xl mx-auto">
                    <div class="mb-12 flex justify-between items-end">
                        <div>
                            <h2 class="text-xs font-bold uppercase tracking-[0.2em] text-neutral-400 mb-2">{{ $sectionTitle ?? 'Overview' }}</h2>
                            <h1 class="font-serif text-4xl font-bold">{{ $pageTitle ?? 'Dashboard' }}</h1>
                        </div>
                        <div>
                            {{ $pageActions ?? '' }}
                        </div>
                    </div>

                    {{ $slot }}
                </div>
            </main>

            <footer class="p-8 text-center border-t border-[#E5E5E5] text-[10px] font-bold uppercase tracking-widest text-neutral-400">
                &copy; {{ date('Y') }} Bizscoop Editorial Panel &bull; v1.0.0
            </footer>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
