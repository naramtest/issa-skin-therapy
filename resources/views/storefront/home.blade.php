<x-store-main-layout>
    <main class="relative">
        <x-home.section.hero-swiper />
        <x-home.section.collection />

        <x-home.section-container
            class="relative flex w-full flex-col items-center gap-16 px-40 py-28 lg:flex-row"
        >
            <x-general.product-gallery />
            <div class="flex w-full flex-col lg:w-[45%]">
                <p>Our Best Seller</p>
                <h2 class="mb-3 mt-3 text-4xl font-bold">
                    SaliCleanse Cleanser
                </h2>
                <p class="mb-3 text-lg">€55.22</p>
                <div class="flex">
                    <span
                        aria-hidden="true"
                        class="rating-star hidden lg:block"
                    ></span>
                </div>
                <div class="no-tailwind my-8">
                    <ul class="text-lg">
                        <li>Suitable for acne prone or oily skin</li>
                        <li>Helps unclog pores</li>
                        <li>
                            Gently exfoliates with
                            <strong>2% salicylic acid</strong>
                        </li>
                    </ul>
                </div>
                <button
                    class="w-full rounded-3xl bg-darkColor py-2 text-lightColor hover:bg-[#333F43]"
                >
                    {{ __("store.Add to Card") }}
                </button>
                <div class="mt-6 flex justify-between px-2">
                    <div class="flex gap-x-2">
                        <span>{{ __("store.Social:") }}</span>
                        <x-layout.header.home.social
                            width="w-5"
                            height="h-5"
                            color="text-black"
                            class="gap-x-2"
                        />
                    </div>
                    <div class="flex gap-x-2">
                        <x-icons.qustion-mark />
                        <span>{{ __("store.Need help? Contact us") }}</span>
                    </div>
                </div>

                <a
                    href="/"
                    class="mt-6 flex items-center justify-between border-t-[1px] border-[#A5BBC4] pt-6"
                >
                    <p class="text-sm font-semibold">View full details</p>
                    <x-icons.arrow-right class="h-5 w-5" />
                </a>
            </div>
        </x-home.section-container>
        <x-home.section.vedio-background />
        <x-home.section.boxes />
        <div class="h-[1000px] w-full"></div>
    </main>

    @pushonce("scripts")
        <script>
            function productSwiper() {
                return {
                    activeIndex: 0,
                    productSwiper1: null,
                    init() {
                        this.productSwiper1 = new Swiper('.product-swiper', {
                            modules: [EffectFade],
                            slidesPerView: 1,
                            speed: 800,
                            effect: 'fade',
                            on: {
                                slideChange: () => {
                                    if (this.productSwiper1) {
                                        this.activeIndex =
                                            this.productSwiper1.realIndex;
                                    }
                                },
                            },
                        });
                    },
                    slideTo(index) {
                        this.productSwiper1?.slideTo(index, 500);
                    },
                    nextSlide() {
                        this.productSwiper1?.slideNext(500);
                    },
                    pervSlide() {
                        this.productSwiper1?.slidePrev(500);
                    },
                };
            }
        </script>
    @endpushonce
</x-store-main-layout>
