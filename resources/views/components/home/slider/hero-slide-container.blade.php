<div
    {{ $attributes->class(["swiper-slide overflow-hidden rounded-[20px]"]) }}
>
    {{ $slot }}
    <div class="absolute bottom-0 w-full px-5 md:px-8 lg:px-[3.75rem]">
        <div
            class="flex flex-col justify-between gap-y-3 border-b pb-5 md:pb-7 lg:flex-row lg:items-end lg:pb-10"
        >
            <div
                class="w-full self-start overflow-hidden md:w-[70%] lg:w-[50%] xl:w-[40%]"
                x-data="{ isActive: false }"
                x-init="
                    () => {
                        $el.setAttribute('data-slide', 'content-wrapper')
                        isActive = true
                    }
                "
            >
                <h2
                    class="translate-y-full text-4xl uppercase text-white transition-transform duration-700 md:text-4xl lg:px-3 lg:text-[3rem] lg:leading-[48px]"
                    :class="isActive ? '!translate-y-0' : '-translate-y-full'"
                >
                    {{ $content }}
                </h2>
            </div>

            <a href="{{ route("shop.index") }}" class="w-[150px] lg:w-[200px]">
                <x-general.button-white-animation
                    class="!py-2 md:!py-3 lg:!py-4"
                >
                    <span class="relative z-10 inline-block">
                        {{ __("store.Shop Now") }}
                    </span>
                </x-general.button-white-animation>
            </a>
        </div>
        <hr class="w-full border-t-[1px] border-white/60" />
        <div class="py-10"></div>
    </div>
</div>
