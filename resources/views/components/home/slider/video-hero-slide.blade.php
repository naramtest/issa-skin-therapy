{{-- video-hero-slide.blade.php --}}

@props([
    "vid",
])
<x-home.slider.hero-slide-container {{ $attributes }}>
    <video
        x-ref="video"
        class="h-full w-full rounded-[20px] object-cover"
        autoplay
        muted
        playsinline
        webkit-playsinline
        loop
        preload="metadata"
        src="{{ $vid }}"
    >
        <source src="{{ $vid }}" type="video/webm" />
        <source
            src="{{ str_replace(".webm", ".mp4", $vid) }}"
            type="video/mp4"
        />
    </video>
    <x-slot:content>
        {{ __("store.Skin That Defies Time") }}
    </x-slot>
</x-home.slider.hero-slide-container>
