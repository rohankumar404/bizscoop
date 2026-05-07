<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin - BizScoop</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="bg-neutral-100 text-[var(--color-primary)] antialiased font-sans" x-data="{ sidebarOpen: false }">
    <div class="min-h-screen flex">
        {{-- Sidebar --}}
        <aside class="fixed inset-y-0 left-0 z-50 w-64 bg-black text-white transform transition-transform duration-300 lg:relative lg:translate-x-0"
               :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">
            <div class="h-full flex flex-col p-6">
                <div class="flex items-center justify-between mb-10">
                    <span class="font-serif text-2xl font-bold italic tracking-tighter">BizScoop Admin</span>
                    <button @click="sidebarOpen = false" class="lg:hidden text-white">&times;</button>
                </div>
                
                <nav class="flex-grow space-y-2">
                    <a href="#" class="flex items-center px-4 py-3 bg-neutral-900 rounded-lg text-sm font-semibold">
                        Dashboard
                    </a>
                    <a href="#" class="flex items-center px-4 py-3 hover:bg-neutral-900 rounded-lg text-sm font-medium transition-colors">
                        All Articles
                    </a>
                    <a href="#" class="flex items-center px-4 py-3 hover:bg-neutral-900 rounded-lg text-sm font-medium transition-colors">
                        Categories
                    </a>
                    <a href="#" class="flex items-center px-4 py-3 hover:bg-neutral-900 rounded-lg text-sm font-medium transition-colors">
                        Media Library
                    </a>
                </nav>

                <div class="mt-auto pt-6 border-t border-neutral-800">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-neutral-800 rounded-full flex items-center justify-center font-bold">A</div>
                        <div>
                            <p class="text-sm font-bold">{{ auth()->user()->name ?? 'Admin' }}</p>
                            <p class="text-xs text-neutral-500">Editor-in-Chief</p>
                        </div>
                    </div>
                </div>
            </div>
        </aside>

        {{-- Main Area --}}
        <div class="flex-1 flex flex-col min-w-0">
            {{-- Header --}}
            <header class="bg-white border-b border-[var(--color-border)] h-16 flex items-center px-8">
                <button @click="sidebarOpen = true" class="lg:hidden mr-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                </button>
                <h1 class="text-lg font-bold">@yield('page-title', 'Dashboard')</h1>
                
                <div class="ml-auto flex items-center space-x-4">
                    <button class="text-sm font-medium text-neutral-500 hover:text-black">View Site</button>
                    <form method="POST" action="/logout">
                        @csrf
                        <button class="text-sm font-bold text-red-600">Logout</button>
                    </form>
                </div>
            </header>

            {{-- Page Content --}}
            <main class="p-8 flex-grow">
                {{ $slot }}
            </main>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
