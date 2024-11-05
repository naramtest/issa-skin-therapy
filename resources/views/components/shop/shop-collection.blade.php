@props([
    "title",
    "img",
    "subtitle",
])

<div
    {{ $attributes->class([" card-hover-trigger !flex flex-col rounded-[15px] bg-[#FAFAFA]"]) }}
>
    <img
        class="rounded-inherit h-[360px] w-full object-cover"
        src="{{ $img }}"
        alt=""
    />
    <div class="px-7 py-5">
        <div class="flex items-center justify-between">
            <h3 class="text-underline text-underline-black text-xl font-bold">
                {{ $title }}
            </h3>
            <x-icons.card-arrow-right class="arrow h-5 w-5" />
        </div>
        <p class="mt-2">{{ $subtitle }}</p>
    </div>
</div>
