@props([
    "vid",
])
{{-- TODO: Why Video Not Always Playing --}}
<x-home.slider.hero-slide-container {{ $attributes }}>
    <video
        class="h-full w-full rounded-[20px] object-cover"
        autoplay
        muted
        playsinline
        loop
        src="{{ $vid }}"
    ></video>
</x-home.slider.hero-slide-container>
