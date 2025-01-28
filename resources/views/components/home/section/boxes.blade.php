@props([
    "bundles",
])
<x-home.section-container
    class="content-x-padding relative z-10 h-[600px] -translate-y-4 bg-lightColor py-14 md:-translate-y-10"
>
    <h2 class="headline-font text-center">
        {{ __("store.Stepwise Skin Therapy Collection Boxes") }}
    </h2>
    <p class="mt-2 text-center text-lg">
        {{ __("store.Each collection is a step towards radiant skin") }}
    </p>

    <div x-data="boxesSwiper()" class="mt-6 w-full">
        <div class="boxes-swiper swiper">
            <div class="swiper-wrapper !h-[400px]">
                @foreach ($bundles as $bundle)
                    <x-home.boxes-slide :bundle="$bundle" />
                @endforeach
            </div>

            <!-- Navigation arrows -->
            <div class="mx-auto mt-6 flex w-[30%] items-center justify-between">
                <div
                    @click="pervSlide()"
                    :class="{ 'text-gray-300 cursor-default': isBeginning, 'cursor-pointer': !isBeginning }"
                    class="duration-4 00 transition-colors"
                >
                    <x-icons.arrow-right class="lt:rotate-180 h-6 w-6" />
                </div>
                <div class="swiper-pagination !static"></div>

                <div
                    @click="nextSlide()"
                    :class="{ 'text-gray-300 cursor-default': isEnd, 'cursor-pointer': !isEnd }"
                    class="duration-4 00 transition-colors"
                >
                    <x-icons.arrow-right class="h-6 w-6 rtl:rotate-180" />
                </div>
            </div>
        </div>
    </div>
    @pushonce("scripts")
        <script>
            function boxesSwiper() {
                return {
                    isBeginning: true,
                    isEnd: false,
                    boxesSwiper: null,
                    init() {
                        this.boxesSwiper = new Swiper('.boxes-swiper', {
                            modules: [Pagination],
                            slidesPerView: 1,
                            spaceBetween: 10,
                            loop: false,
                            breakpoints: {
                                // when window width is >= 320px
                                725: {
                                    slidesPerView: 2,
                                    spaceBetween: 10,
                                },
                                // when window width is >= 480px
                                1150: {
                                    slidesPerView: 3,
                                    spaceBetween: 20,
                                },
                            },
                            pagination: {
                                el: '.swiper-pagination',
                                clickable: true,
                                type: 'bullets',
                            },
                            on: {
                                reachBeginning: () => {
                                    this.isBeginning = true;
                                    this.isEnd = false;
                                },
                                reachEnd: () => {
                                    this.isBeginning = false;

                                    this.isEnd = true;
                                },
                            },
                        });
                    },
                    nextSlide() {
                        if (!this.isEnd) {
                            this.boxesSwiper.slideNext(1000);
                        }
                    },
                    pervSlide() {
                        if (!this.isBeginning) {
                            this.boxesSwiper.slidePrev(1000);
                        }
                    },
                };
            }
        </script>
    @endpushonce
</x-home.section-container>
