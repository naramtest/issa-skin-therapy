@props([
    "imgUrl",
])

<x-home.slider.hero-slide-container {{ $attributes }}>
    <img
        class="h-full w-full rounded-[20px] object-cover"
        src="{{ $imgUrl }}"
        alt=""
    />
    <x-slot:content>
        shop the collection and
        <span class="ms-2 font-bold">save 30%</span>
    </x-slot>
</x-home.slider.hero-slide-container>
