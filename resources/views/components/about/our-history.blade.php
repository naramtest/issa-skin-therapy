<section
    class="padding-from-side-menu mt-[40px] rounded-[20px] bg-black py-6 text-white lg:py-[80px]"
>
    <div class="flex flex-col gap-4 lg:flex-row lg:gap-8">
        <h2
            class="text-3xl font-[600] lg:w-[30%] lg:pb-10 lg:text-[95px] lg:font-[800] lg:leading-[100px] rtl:text-[60px]"
        >
            {{ __("store.Our History") }}
        </h2>
        <div class="flex flex-col lg:w-[70%] lg:flex-row">
            <img
                class="h-[330px] rounded-t-3xl object-cover lg:h-full lg:min-h-[420px] lg:w-[40%] lg:rounded-t-none"
                src="{{ asset("storage/images/about/our-history.webp") }}"
                alt="background"
            />
            <div
                class="rounded-b-3xl bg-[#202020] px-4 py-6 lg:w-[60%] lg:!rounded-e-[25px] lg:rounded-b-none lg:px-[50px] lg:py-[60px]"
            >
                <h3 class="text-[24px] font-medium">
                    {{ __("store.Healing in the Gulf") }}
                </h3>
                <p class="mt-6 rtl:leading-[30px]">
                    {{ __("store.Venturing beyond borders") }}
                </p>
            </div>
        </div>
    </div>
    <div class="mt-8 flex flex-col lg:mt-12 lg:w-[70%] lg:flex-row">
        <div
            class="rounded-t-3xl bg-[#202020] px-4 py-6 lg:w-[60%] lg:!rounded-s-[25px] lg:rounded-t-none lg:px-[50px] lg:py-[60px]"
        >
            <h3 class="text-[24px] font-medium">
                {{ __("store.A Life Well-Lived") }}
            </h3>
            <p class="mt-6 rtl:leading-[30px]">
                {{ __("store.Issa Bachour left behind a legacy of healing and beauty") }}
            </p>
        </div>
        <img
            style="object-position: top center"
            class="h-full min-h-[430px] object-cover lg:min-h-[420px] lg:w-[40%]"
            src="{{ asset("storage/images/about/life-well-lived.webp") }}"
            alt="background"
        />
    </div>
    <img
        class="mt-12"
        src="{{ asset("storage/images/about/time.webp") }}"
        alt="Time Line"
    />
</section>
