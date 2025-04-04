<x-home.section-container
    class="card-overlay relative mt-10 flex h-[450px] items-center justify-center overflow-hidden lg:h-[600px]"
>
    <video
        class="absolute inset-0 h-full w-full object-cover"
        autoplay
        muted
        playsinline
        webkit-playsinline
        loop
        preload="auto"
        src="{{ asset("storage/video/patent-delivery-technology.webm") }}"
    ></video>
    <div class="z-[10] flex flex-col items-center text-white lg:w-[60%]">
        <p class="text-[13px] font-[200] uppercase tracking-[2px]">
            {{ __("store.Made in USA") }}
        </p>
        <h2 class="headline-font mt-4 text-center">
            {{ __("store.Patent Delivery Technology") }}
        </h2>
    </div>
</x-home.section-container>
