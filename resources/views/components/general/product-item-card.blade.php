@props([
    "title",
    "img",
    "subtitle",
    "price",
])

<div
    {{ $attributes->class(["!flex flex-col rounded-[15px] bg-[#F4F4F4] p-2"]) }}
>
    <div class="group relative">
        <img
            class="h-[400px] w-full rounded-[10px] object-cover"
            src="{{ $img }}"
            alt=""
        />
        <a
            class="absolute bottom-16 start-1/2 -translate-x-1/2 translate-y-full scale-0 rounded-[50px] bg-darkColor px-5 py-2 text-sm text-white opacity-0 transition-all duration-500 group-hover:translate-y-0 group-hover:scale-100 group-hover:opacity-100"
            href="/"
        >
            {{ __("store.Add to cart") }}
        </a>
    </div>
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
