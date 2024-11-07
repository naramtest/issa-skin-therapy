<x-home.section-container
    class="card-overlay relative mt-16 flex h-[600px] items-center justify-center overflow-hidden"
>
    <img
        class="absolute inset-0 h-full w-full object-cover"
        src="{{ asset("storage/images/02.webp") }}"
        alt="background Image"
    />
    <div class="z-[10] flex w-[60%] flex-col items-center text-white">
        <h2 class="w-[70%] text-center text-5xl leading-[48px]">
            Share your before and after to get a nice gift!
        </h2>

        <a
            class="mt-10 flex w-fit items-center rounded-[50px] border border-lightColor bg-[#333F4396] px-8 py-3 text-[15px] font-medium text-black text-white transition-colors duration-200 hover:border-[#333F43] hover:bg-[#333F43]"
            href=""
        >
            <span>{{ __("store.Explore Sales") }}</span>
            <x-icons.arrow-right class="ms-3 h-4 w-4" />
        </a>
    </div>
</x-home.section-container>
