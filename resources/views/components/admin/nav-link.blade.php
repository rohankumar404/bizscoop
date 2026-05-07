@props(['active' => false, 'href' => '#'])

@php
    $classes = ($active ?? false)
                ? 'flex items-center px-4 py-3 bg-neutral-900 text-white rounded-none text-sm font-bold transition-all border-l-2 border-white'
                : 'flex items-center px-4 py-3 text-neutral-400 hover:text-white hover:bg-neutral-900 rounded-none text-sm font-medium transition-all border-l-2 border-transparent';
@endphp

<a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
