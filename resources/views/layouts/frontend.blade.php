<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <x-seo :title="$title ?? null" :description="$description ?? null" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Instrument+Serif:ital@0;1&display=swap" rel="stylesheet">

    <!-- Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="bg-[var(--color-background)] text-[var(--color-primary)] selection:bg-black selection:text-white">
    <div id="app" class="flex flex-col min-h-screen">
        {{-- Navbar --}}
        <header class="border-b border-[var(--color-border)] sticky top-0 bg-white/80 backdrop-blur-md z-50">
            <x-container class="flex justify-between items-center h-20">
                <a href="/" class="font-serif text-3xl font-bold tracking-tighter">
                    BizScoop
                </a>
                
                <nav class="hidden md:flex space-x-8 text-sm font-medium uppercase tracking-widest">
                    <a href="#" class="hover:text-neutral-500 transition-colors">World</a>
                    <a href="#" class="hover:text-neutral-500 transition-colors">Business</a>
                    <a href="#" class="hover:text-neutral-500 transition-colors">Tech</a>
                    <a href="#" class="hover:text-neutral-500 transition-colors">Culture</a>
                </nav>

                <div class="flex items-center space-x-4">
                    <button class="text-sm font-bold uppercase border-b-2 border-black">Subscribe</button>
                </div>
            </x-container>
        </header>

        {{-- Main Content --}}
        <main class="flex-grow py-12">
            {{ $slot }}
        </main>

        {{-- Footer --}}
        <footer class="bg-[var(--color-surface)] border-t border-[var(--color-border)] py-20">
            <x-container>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-12">
                    <div class="col-span-1 md:col-span-2">
                        <h2 class="font-serif text-3xl font-bold mb-6">BizScoop</h2>
                        <p class="text-neutral-500 max-w-sm leading-relaxed">
                            Delivering high-integrity journalism for the modern professional. Stay ahead with deep insights and curated news.
                        </p>
                    </div>
                    <div>
                        <h3 class="text-xs font-bold uppercase tracking-widest mb-6">Sections</h3>
                        <ul class="space-y-4 text-sm font-medium">
                            <li><a href="#" class="hover:underline">Global News</a></li>
                            <li><a href="#" class="hover:underline">Market Analysis</a></li>
                            <li><a href="#" class="hover:underline">Innovation</a></li>
                        </ul>
                    </div>
                    <div>
                        <h3 class="text-xs font-bold uppercase tracking-widest mb-6">Support</h3>
                        <ul class="space-y-4 text-sm font-medium">
                            <li><a href="#" class="hover:underline">Help Center</a></li>
                            <li><a href="#" class="hover:underline">Terms of Service</a></li>
                            <li><a href="#" class="hover:underline">Privacy Policy</a></li>
                        </ul>
                    </div>
                </div>
                <div class="mt-20 pt-8 border-t border-[var(--color-border)] text-xs text-neutral-400 text-center uppercase tracking-widest">
                    &copy; {{ date('Y') }} BizScoop Media Group. All Rights Reserved.
                </div>
            </x-container>
        </footer>
    </div>

    @stack('scripts')
</body>
</html>
