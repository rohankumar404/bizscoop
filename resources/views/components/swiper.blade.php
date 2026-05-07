@props(['id' => 'swiper-' . uniqid()])

<div id="{{ $id }}" class="swiper w-full h-64 rounded-xl overflow-hidden">
    <div class="swiper-wrapper">
        {{ $slot }}
    </div>
    
    <!-- Optional: Add Pagination/Navigation -->
    <div class="swiper-pagination"></div>
    <div class="swiper-button-prev"></div>
    <div class="swiper-button-next"></div>
</div>

@once
    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            new Swiper('#{{ $id }}', {
                loop: true,
                pagination: {
                    el: '.swiper-pagination',
                },
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },
            });
        });
    </script>
    @endpush
@endonce
