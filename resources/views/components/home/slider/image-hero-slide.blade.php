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
        {{ __("store.shop the collection and") }}
        <span class="font-bold">{{ " " . __("store.save 30%") }}</span>
    </x-slot>
</x-home.slider.hero-slide-container>
