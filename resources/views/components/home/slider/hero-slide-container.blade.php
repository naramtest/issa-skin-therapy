<div
    {{ $attributes->class(["swiper-slide overflow-hidden rounded-[20px]"]) }}
>
    {{ $slot }}
    <div class="absolute bottom-0 w-full px-[3.75rem]">
        <div class="flex items-end justify-between border-b pb-10">
            <div class="w-[50%] self-start">
                <h2
                    class="w-[60%] px-3 text-[2.75rem] uppercase leading-[48px] text-white"
                >
                    shop the collection and
                    <span class="ms-2 font-bold">save 30%</span>
                </h2>
            </div>
            <div class="">
                <a
                    href=""
                    class="hover:bg-lightAccentColor rounded-[50px] bg-lightColor px-[30px] py-[15px]"
                >
                    Shop Now
                </a>
            </div>
        </div>
        <hr class="w-full border-t-[1px] border-white/60" />
        <div class="py-10"></div>
    </div>
</div>
