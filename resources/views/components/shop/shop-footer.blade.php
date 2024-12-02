<x-home.section-container
    style="
                background-image: url({{asset('storage/images/01.webp')}});
                background-position: 20% 0px;
                background-repeat: no-repeat;
                background-size: cover;
            "
    class="card-overlay relative mt-10 flex h-[600px] items-center justify-center overflow-hidden before:opacity-30"
>
    <div class="z-[10] flex w-[60%] flex-col items-center text-white">
        <p class="text-[13px] font-[200] uppercase tracking-[2px]">
            {{ __("store.Made in USA") }}
        </p>
        <h2 class="headline-font mt-4 text-center">
            {{ __("store.Patent Delivery Technology") }}
        </h2>
        <p class="mt-10 text-[13px] font-[200] uppercase tracking-[2px]">
            {{ __("store.Crafted By Dermatologist and make a slide of nice photos folder") }}
        </p>

        <a
            class="mt-6 rounded-[50px] bg-lightColor px-12 py-3 text-darkColor transition-colors duration-300 hover:bg-[#CCDBE1]"
            href="{{ route("about.index") }}"
        >
            {{ __("store.About Us") }}
        </a>
    </div>
</x-home.section-container>
