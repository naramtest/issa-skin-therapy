<x-home.section-container
    class="card-overlay relative mt-3 flex min-h-[450px] items-center justify-center overflow-hidden lg:mt-8 lg:min-h-[600px]"
>
    <video
        class="absolute inset-0 h-full w-full object-cover"
        autoplay
        muted
        playsinline
        webkit-playsinline
        loop
        preload="auto"
        src="{{ asset("storage/video/hoempage3.webm") }}"
    ></video>
    <div
        class="z-[10] flex w-full flex-col items-center px-4 text-white lg:w-[60%] lg:px-0"
    >
        <h2 class="headline-font text-center lg:w-[60%]">
            {{ __("store.Our Patent Delivery Technology (PET)") }}
        </h2>
        <p class="font-xl mt-4 text-center">
            {{ __("store.The ISSA SKIN THERAPY team recognized PET™’s potential in") }}
        </p>
        <a class="group mt-10 w-fit" href="{{ route("about.index") }} ">
            <x-general.button-white-animation
                class="!w-fit !px-6 ltr:!py-4 rtl:!py-1"
            >
                <span
                    class="relative z-10 inline-block text-[15px] font-medium rtl:leading-[40px]"
                >
                    {{ __("store.Know More") }}
                </span>
                <x-icons.arrow-right class="z-10 ms-3 h-4 w-4 rtl:rotate-180" />
            </x-general.button-white-animation>
        </a>
    </div>
</x-home.section-container>
