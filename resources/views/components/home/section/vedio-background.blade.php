<x-home.section-container
    class="card-overlay relative mt-10 flex h-[600px] items-center justify-center overflow-hidden"
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
    <div class="z-[10] flex w-[60%] flex-col items-center text-white">
        <h2 class="headline-font w-[60%] text-center">
            {{ __("store.Our Patent Delivery Technology (PET)") }}
        </h2>
        <p class="font-lg mt-4 text-center">
            {{ __("store.The ISSA SKIN THERAPY team recognized PET™’s potential in") }}
        </p>
        <a
            class="mt-10 flex w-fit items-center rounded-[50px] bg-lightColor px-6 py-4 text-[15px] font-medium text-black transition-colors duration-200 hover:bg-lightAccentColor"
            href=""
        >
            <span>Know More</span>
            <x-icons.arrow-right class="ms-3 h-4 w-4 text-black" />
        </a>
    </div>
</x-home.section-container>
