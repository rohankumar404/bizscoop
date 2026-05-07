<x-frontend-layout>
    <x-seo title="The Future of Business Journalism" />

    <x-container>
        {{-- Hero Section --}}
        <div class="border-b border-black pb-20 mb-20">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-end">
                <div class="lg:col-span-8">
                    <p class="text-xs font-bold uppercase tracking-widest mb-6 flex items-center">
                        <span class="w-2 h-2 bg-red-600 rounded-full mr-2"></span>
                        Trending Now
                    </p>
                    <x-editorial.heading level="1" size="2xl" class="mb-8">
                        The Great <span class="italic">BizScoop</span> Paradigm: Why Neutrality is the New Gold.
                    </x-editorial.heading>
                </div>
                <div class="lg:col-span-4">
                    <p class="text-lg leading-relaxed text-neutral-600 mb-8">
                        In an era of polarized media, we're returning to the roots of high-integrity journalism. Discover how data-driven insights are reshaping the corporate landscape.
                    </p>
                    <x-ui.button variant="outline" size="md">
                        Read the Manifesto
                    </x-ui.button>
                </div>
            </div>
        </div>

        {{-- Featured Content Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
            <div class="group cursor-pointer">
                <div class="aspect-video bg-neutral-100 mb-6 overflow-hidden">
                    {{-- Placeholder for image --}}
                    <div class="w-full h-full bg-neutral-200 group-hover:scale-105 transition-transform duration-500"></div>
                </div>
                <p class="text-[10px] font-bold uppercase tracking-widest text-neutral-400 mb-3">Markets</p>
                <h3 class="font-serif text-2xl font-bold mb-4 group-hover:underline">Global indices hit record highs amidst tech surge.</h3>
                <p class="text-sm text-neutral-500 leading-relaxed">Analysis of the latest market trends and what they mean for your portfolio.</p>
            </div>

            <div class="group cursor-pointer">
                <div class="aspect-video bg-neutral-100 mb-6 overflow-hidden">
                    <div class="w-full h-full bg-neutral-300 group-hover:scale-105 transition-transform duration-500"></div>
                </div>
                <p class="text-[10px] font-bold uppercase tracking-widest text-neutral-400 mb-3">Innovation</p>
                <h3 class="font-serif text-2xl font-bold mb-4 group-hover:underline">AI Governance: The next frontier for global regulators.</h3>
                <p class="text-sm text-neutral-500 leading-relaxed">How governments are racing to keep up with the pace of artificial intelligence.</p>
            </div>

            <div class="group cursor-pointer">
                <div class="aspect-video bg-neutral-100 mb-6 overflow-hidden">
                    <div class="w-full h-full bg-neutral-400 group-hover:scale-105 transition-transform duration-500"></div>
                </div>
                <p class="text-[10px] font-bold uppercase tracking-widest text-neutral-400 mb-3">Culture</p>
                <h3 class="font-serif text-2xl font-bold mb-4 group-hover:underline">The subtle art of professional disconnection.</h3>
                <p class="text-sm text-neutral-500 leading-relaxed">Why the best CEOs are turning off their phones after 6 PM.</p>
            </div>
        </div>
    </x-container>
</x-frontend-layout>
