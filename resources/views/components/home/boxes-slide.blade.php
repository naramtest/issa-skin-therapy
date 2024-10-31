@props([
    "image",
])
<div {{ $attributes->class(["swiper-slide"]) }}>
    <img
        class="h-full w-full rounded-[15px] object-cover"
        src="{{ $image }}"
        alt=""
    />
</div>
