<x-home.section-container
    class="card-overlay relative mt-[20rem] flex h-[450px] items-center justify-center overflow-hidden lg:mt-16 lg:h-[600px]"
>
    <img
        class="absolute inset-0 h-full w-full object-cover"
        src="{{ asset("storage/images/02.webp") }}"
        alt="background Image"
    />
    <div
        class="z-[10] flex w-full flex-col items-center px-0 text-white lg:w-[60%] lg:px-0"
    >
        <h2
            class="w-full text-center text-3xl lg:w-[70%] lg:text-5xl lg:leading-[48px]"
        >
            {{ __("store.Share your before and after to get a nice gift!") }}
        </h2>

        <a
            class="mt-10 flex w-fit items-center rounded-[50px] border border-lightColor bg-[#333F4396] px-8 py-3 text-[15px] font-medium text-white transition-colors duration-200 hover:border-[#333F43] hover:bg-[#333F43]"
            href="{{ route("shop.index") }}"
        >
            <span>{{ __("store.Explore Sales") }}</span>
            <x-icons.arrow-right class="ms-3 h-4 w-4" />
        </a>
    </div>
</x-home.section-container>
