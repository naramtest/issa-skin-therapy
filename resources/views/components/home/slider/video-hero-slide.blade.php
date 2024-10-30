@props([
    "vid",
])
<x-home.slider.hero-slide-container {{ $attributes }}>
    <video
        class="h-full w-full rounded-[20px] object-cover"
        autoplay
        muted
        playsinline
        webkit-playsinline
        loop
        preload="auto"
        src="{{ $vid }}"
    ></video>
    <x-slot:content>Skin That Defies Time</x-slot>
</x-home.slider.hero-slide-container>
