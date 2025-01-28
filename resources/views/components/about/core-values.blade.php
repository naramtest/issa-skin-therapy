<section
    class="padding-from-side-menu mt-16 flex flex-col gap-5 rounded-b-[20px] border-b-[1px] border-[#A6BCC599] pb-20 text-[17px] md:mt-[80px] md:flex-row"
>
    <div class="flex flex-col justify-center md:w-[30%]">
        <div>{{ __("store.Our Core Values") }}</div>
        <h2 class="headline-font mt-[20px]">
            {{ __("store.Crafted by Dermatologist") }}
        </h2>
        <ol style="list-style: auto; padding: revert" class="mt-6">
            <li>
                {{ __("store.Crafted by Dr. Issa: Dermatologist-Approved Skincare") }}
            </li>
            <li>
                {{ __("store.Revolutionary Patent Delivery Technology (PET Delivery)") }}
            </li>
            <li>{{ __("store.Premium Quality, Made in ") }}</li>
        </ol>
    </div>
    <div
        class="grid grid-cols-1 gap-[20px] text-2xl font-medium text-white md:w-[70%] md:grid-cols-3 rtl:text-base"
    >
        <div class="relative overflow-hidden">
            <img
                class="h-[360px] w-full rounded-2xl"
                src="{{ asset("storage/images/shop-bg.webp") }}"
                alt="background"
            />
            <div
                style="backdrop-filter: blur(5px)"
                class="absolute bottom-0 w-full rounded-2xl bg-[#ADADAD3D] px-7 py-5"
            >
                <p class="">
                    {{ __("store.Crafted by Dermatologist") }}
                </p>
            </div>
        </div>
        <div class="relative overflow-hidden">
            <img
                class="h-[360px] w-full rounded-2xl"
                src="{{ asset("storage/images/01.webp") }}"
                alt="background"
            />
            <div
                style="backdrop-filter: blur(5px)"
                class="absolute bottom-0 w-full rounded-2xl bg-[#ADADAD3D] px-7 py-5"
            >
                <p class="">
                    {{ __("store.Patent Delivery Technology") }}
                </p>
            </div>
        </div>
        <div class="relative overflow-hidden">
            <img
                class="h-[360px] w-full rounded-2xl"
                src="{{ asset("storage/images/about/about-3.webp") }}"
                alt="background"
            />
            <div
                style="backdrop-filter: blur(5px)"
                class="absolute bottom-0 w-full rounded-2xl bg-[#ADADAD3D] px-7 py-5"
            >
                <p class="">
                    {{ __("store.Premium Quality, Made in ") }}
                </p>
            </div>
        </div>
    </div>
</section>
