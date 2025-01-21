<x-home.section-container
    style="
                background-image: url({{asset('storage/images/01.webp')}});
                background-position: 20% 0px;
                background-repeat: no-repeat;
                background-size: cover;
            "
    class="card-overlay relative mt-10 flex h-[450px] items-center justify-center overflow-hidden before:opacity-30 lg:h-[600px]"
>
    <div
        class="z-[10] flex w-full flex-col items-center px-4 text-center text-white lg:w-[60%] lg:px-0"
    >
        <p class="text-[13px] font-[200] uppercase tracking-[2px]">
            {{ __("store.Made in USA") }}
        </p>
        <h2 class="headline-font mt-4 text-center">
            {{ __("store.Patent Delivery Technology") }}
        </h2>
        <p class="mt-10 text-[13px] font-[200] uppercase tracking-[2px]">
            {{ __("store.Crafted By Dermatologist and make a slide of nice photos folder") }}
        </p>

        <a class="mt-6" href="{{ route("about.index") }}">
            <x-general.button-white-animation class="px-12 py-3">
                <span class="z-10">
                    {{ __("store.About Us") }}
                </span>
            </x-general.button-white-animation>
        </a>
    </div>
</x-home.section-container>
