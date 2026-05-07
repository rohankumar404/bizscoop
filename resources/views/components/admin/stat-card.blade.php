@props(['title', 'value', 'change' => null, 'trend' => 'up'])

<div class="bg-white border border-[#E5E5E5] p-8 transition-shadow hover:shadow-lg group">
    <div class="flex justify-between items-start mb-6">
        <h3 class="text-xs font-bold uppercase tracking-widest text-neutral-400 group-hover:text-black transition-colors">{{ $title }}</h3>
        @if($change)
            <span class="text-[10px] font-bold px-2 py-1 {{ $trend === 'up' ? 'text-green-700 bg-green-50' : 'text-red-700 bg-red-50' }}">
                {{ $trend === 'up' ? '↑' : '↓' }} {{ $change }}
            </span>
        @endif
    </div>
    <div class="flex items-end space-x-2">
        <p class="font-serif text-4xl font-bold tracking-tighter">{{ $value }}</p>
    </div>
</div>
