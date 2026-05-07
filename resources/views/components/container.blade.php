@props(['type' => 'landing'])

@php
    $class = match($type) {
        'article' => 'container-article',
        'landing' => 'container-landing',
        default => 'container-landing',
    };
@endphp

<div {{ $attributes->merge(['class' => $class]) }}>
    {{ $slot }}
</div>
