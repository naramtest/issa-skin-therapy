@props([
    "imgUrl",
])

<x-home.slider.hero-slide-container {{ $attributes }}>
    <img
        class="h-full w-full rounded-[20px] object-cover"
        src="{{ $imgUrl }}"
        alt=""
    />
</x-home.slider.hero-slide-container>
