<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Bizscoop') }} - Access Portal</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Merriweather:ital,wght@0,300;0,400;0,700;0,900;1,300&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <style>
            body, html {
                margin: 0;
                padding: 0;
                height: 100%;
                background-color: #fafafa;
                font-family: 'Inter', sans-serif;
            }
        </style>
    </head>
    <body class="antialiased text-gray-900 bg-[#fafafa]">
        <div class="min-h-screen flex flex-col md:flex-row bg-[#fafafa]">
            {{-- Left Banner (Visible on md and up) --}}
            <div class="hidden md:flex md:w-[42%] bg-[#0A0A0A] text-white flex-col justify-between p-12 relative overflow-hidden select-none">
                {{-- Decorative background gradient/glow --}}
                <div class="absolute -top-40 -left-40 w-96 h-96 bg-blue-600 rounded-full filter blur-[150px] opacity-15 pointer-events-none"></div>
                <div class="absolute -bottom-40 -right-40 w-96 h-96 bg-purple-600 rounded-full filter blur-[150px] opacity-10 pointer-events-none"></div>
                
                {{-- Top Logo & Back link --}}
                <div class="relative z-10">
                    <a href="/" class="inline-flex items-center space-x-2 text-white/60 hover:text-white transition-colors group">
                        <svg class="w-4 h-4 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        <span class="text-[10px] font-bold uppercase tracking-widest">Back to site</span>
                    </a>
                </div>

                {{-- Branding --}}
                <div class="relative z-10 my-auto">
                    <div style="font-family:'Merriweather',serif;" class="text-4xl lg:text-5xl font-black tracking-tight leading-none mb-4 uppercase">
                        BIZSCOOP
                    </div>
                    <div class="h-[3px] w-12 bg-blue-600 mb-6"></div>
                    <p class="text-base text-gray-400 font-medium leading-relaxed max-w-sm">
                        High integrity business journalism for professionals across the GCC and MENA region.
                    </p>
                </div>

                {{-- Footer info --}}
                <div class="relative z-10 text-[10px] font-bold uppercase tracking-widest text-neutral-600">
                    &copy; {{ date('Y') }} Bizscoop Media Group. All Rights Reserved.
                </div>
            </div>

            {{-- Right Form Area --}}
            <div class="flex-1 flex flex-col justify-center items-center p-6 sm:p-12 bg-[#fafafa]">
                {{-- Mobile Header (Only visible below md) --}}
                <div class="md:hidden w-full max-w-md mb-8 flex justify-between items-center">
                    <a href="/" style="font-family:'Merriweather',serif;" class="text-2xl font-black tracking-tight text-black uppercase">
                        BIZSCOOP
                    </a>
                    <a href="/" class="text-[10px] font-bold uppercase tracking-widest text-gray-500 hover:text-black transition-colors">
                        Back to site
                    </a>
                </div>

                {{-- The Card Container --}}
                <div class="w-full max-w-md bg-white border border-[#eaeaea] shadow-[0_12px_40px_rgba(0,0,0,0.02)] rounded-none p-8 sm:p-10">
                    {{ $slot }}
                </div>
                
                {{-- Mobile Footer --}}
                <div class="md:hidden text-center text-[10px] font-bold uppercase tracking-widest text-neutral-400 mt-8">
                    &copy; {{ date('Y') }} Bizscoop Media Group.
                </div>
            </div>
        </div>
    </body>
</html>
