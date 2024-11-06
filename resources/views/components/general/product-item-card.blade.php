@props([
    "title",
    "img",
    "subtitle",
    "price",
])

<div
    {{ $attributes->class(["!flex flex-col rounded-[15px] bg-[#F4F4F4] p-2"]) }}
>
    <img
        class="h-[400px] w-full rounded-[10px] object-cover"
        src="{{ $img }}"
        alt=""
    />
    <div class="px-2 pb-3 pt-5">
        <div class="flex items-center justify-between gap-x-3">
            <div>
                <p class="text-xs text-[#8C92A4]">{{ $subtitle }}</p>
                <h3 class="mt-3 text-lg font-semibold">
                    {{ $title }}
                </h3>
            </div>
            <p>{{ $price }}</p>
        </div>
    </div>
</div>
