<section
    class="mt-[40px] rounded-[20px] bg-black px-4 py-6 text-white md:p-[40px] md:px-0 md:py-0 lg:p-[80px]"
>
    <div class="flex flex-col gap-4 md:flex-row md:gap-8">
        <h2
            class="text-3xl font-[600] md:w-[30%] md:pb-10 md:text-[95px] md:font-[800] md:leading-[100px]"
        >
            {{ __("store.Our History") }}
        </h2>
        <div class="flex flex-col md:w-[70%] md:flex-row">
            <img
                class="md:rounded-t-0 h-[330px] rounded-t-3xl object-cover md:h-full md:min-h-[420px] md:w-[40%]"
                src="{{ asset("storage/images/about/our-history.webp") }}"
                alt="background"
            />
            <div
                class="md:rounded-b-0 rounded-b-3xl bg-[#202020] px-4 py-6 md:w-[60%] md:rounded-e-2xl md:px-[50px] md:py-[60px]"
            >
                <h3 class="text-[24px] font-medium">
                    {{ __("store.Healing in the Gulf") }}
                </h3>
                <p class="mt-6 text-[#8C92A4]">
                    {{ __("store.Venturing beyond borders") }}
                </p>
            </div>
        </div>
    </div>
    <div class="mt-8 flex flex-col md:mt-12 md:w-[70%] md:flex-row">
        <div
            class="md:rounded-t-0 rounded-t-3xl bg-[#202020] px-4 py-6 md:w-[60%] md:rounded-s-2xl md:px-[50px] md:py-[60px]"
        >
            <h3 class="text-[24px] font-medium">
                {{ __("store.A Life Well-Lived") }}
            </h3>
            <p class="mt-6 text-[#8C92A4]">
                {{ __("store.Issa Bachour left behind a legacy of healing and beauty") }}
            </p>
        </div>
        <img
            class="h-[430px] object-cover md:min-h-[420px] md:w-[40%]"
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
