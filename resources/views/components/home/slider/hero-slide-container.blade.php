<div
    {{ $attributes->class(["swiper-slide overflow-hidden rounded-[20px]"]) }}
>
    {{ $slot }}
    <div class="absolute bottom-0 w-full px-[3.75rem]">
        <div class="flex items-end justify-between border-b pb-10">
            <div
                class="w-[50%] self-start overflow-hidden"
                x-data="{ isActive: false }"
                x-init="
                    () => {
                        $el.setAttribute('data-slide', 'content-wrapper')
                        isActive = true
                    }
                "
            >
                <h2
                    class="w-[60%] translate-y-full px-3 text-[3rem] font-semibold uppercase leading-[48px] text-white transition-transform duration-700"
                    :class="isActive ? '!translate-y-0' : '-translate-y-full'"
                >
                    {{ $content }}
                </h2>
            </div>
            <div>
                <a
                    href=""
                    class="rounded-[50px] bg-lightColor px-[30px] py-[15px] hover:bg-lightAccentColor"
                >
                    {{ __("store.Shop Now") }}
                </a>
            </div>
        </div>
        <hr class="w-full border-t-[1px] border-white/60" />
        <div class="py-10"></div>
    </div>
</div>
