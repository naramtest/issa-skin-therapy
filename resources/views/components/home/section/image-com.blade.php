<x-home.section-container class="py-14">
    <div class="flex flex-col items-center">
        <p class="text-center text-lg">
            {{ __("store.Skin That Defies Times") }}
        </p>
        <h2 class="headline-font gradient-text mt-2 text-[#333F43]">
            {{ __("store.See The Difference") }}
        </h2>
    </div>
    <x-general.image-comparison
        before-image="{{ asset('storage/test/home-before.jpeg') }}"
        after-image="{{ asset('storage/test/home-after.jpeg') }}"
        before-alt="Product before modification"
        after-alt="Product after modification"
        class="mt-9 h-[750px]"
    />
    <div class="mt-12">
        <div x-data="marqueeText">
            <div class="marquee-container overflow-hidden">
                <div x-ref="marqueeText" class="flex items-center gap-x-6">
                    <span>
                        <x-icons.bigger-sign />
                    </span>
                    <span
                        class="black-text-stroke text-nowrap text-[130px] font-bold"
                    >
                        {{ __("store.A-CLEAR") }}
                    </span>
                    <span>
                        <x-icons.bigger-sign />
                    </span>
                    <span
                        class="black-text-stroke text-nowrap text-[130px] font-bold"
                    >
                        {{ __("store.X-AGE") }}
                    </span>
                </div>
            </div>
        </div>
    </div>
</x-home.section-container>
