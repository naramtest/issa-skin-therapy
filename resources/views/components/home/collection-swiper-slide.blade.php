<div
    {{ $attributes->class(["swiper-slide card-hover-trigger !flex flex-col rounded-[15px] bg-[#FAFAFA]"]) }}
>
    <img
        class="rounded-inherit flex-1 object-cover"
        src="{{ asset("storage/test/hero2.webp") }}"
        alt=""
    />
    <div class="px-7 py-5">
        <div class="flex items-center justify-between">
            <h3 class="text-underline text-underline-black text-xl font-bold">
                {{ __("store.Shop by collection") }}
            </h3>
            <x-icons.card-arrow-right class="arrow h-5 w-5" />
        </div>
        <p class="mt-2">{{ __("store.Check out all") }}</p>
    </div>
</div>
