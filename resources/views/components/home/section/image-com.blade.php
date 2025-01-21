<x-home.section-container class="mt-20 pt-8">
    <div class="flex flex-col items-center">
        <p class="text-center text-lg">
            {{ __("store.Skin That Defies Times") }}
        </p>
        <h2 class="headline-font gradient-text mt-2 text-[#333F43]">
            {{ __("store.See The Difference") }}
        </h2>
    </div>
    <x-general.image-comparison
        before-image="{{ asset('storage/images/home-before.webp') }}"
        after-image="{{ asset('storage/images/home-after.webp') }}"
        before-alt="Product before modification"
        after-alt="Product after modification"
        class="mt-9 h-[400px] lg:h-[750px]"
    />
    <div class="mt-8">
        <x-marquee :repeat="15" :speed="50" :gap="50">
            <span>
                <x-icons.bigger-sign />
            </span>
            <span
                class="black-text-stroke text-nowrap text-[100px] font-bold lg:text-[130px]"
            >
                {{ __("store.A-CLEAR") }}
            </span>
            <span>
                <x-icons.bigger-sign />
            </span>
            <span
                class="black-text-stroke text-nowrap text-[100px] font-bold lg:text-[130px]"
            >
                {{ __("store.X-AGE") }}
            </span>
        </x-marquee>
    </div>
</x-home.section-container>
