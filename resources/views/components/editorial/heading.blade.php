@props([
    'level' => 1,
    'size' => '2xl',
    'italic' => false,
])

@php
    $baseClass = 'font-serif leading-tight tracking-tighter text-black';
    
    $sizes = [
        'xs' => 'text-lg md:text-xl',
        'sm' => 'text-xl md:text-2xl',
        'md' => 'text-2xl md:text-4xl',
        'lg' => 'text-4xl md:text-6xl',
        'xl' => 'text-5xl md:text-7xl',
        '2xl' => 'text-6xl md:text-8xl',
    ];

    $tag = "h{$level}";
    $sizeClass = $sizes[$size] ?? $sizes['md'];
    $italicClass = $italic ? 'italic' : '';
@endphp

<{{ $tag }} {{ $attributes->merge(['class' => "$baseClass $sizeClass $italicClass"]) }}>
    {{ $slot }}
</{{ $tag }}>
