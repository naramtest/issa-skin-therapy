<x-store-main-layout>
    <main class="relative">
        <x-home.section.hero-swiper />
        <x-home.section.collection />

        <x-home.section-container
            class="relative flex w-full flex-col items-start gap-5 px-40 py-28 lg:flex-row"
        >
            <x-general.product-gallery />
            <div class="w-full lg:w-[45%]"></div>
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
