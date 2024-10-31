<x-home.section-container
    class="content-x-padding relative z-10 h-[600px] -translate-y-10 bg-lightColor py-14"
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
                <x-home.boxes-slide image="{{asset('storage/test/1.webp')}}" />
                <x-home.boxes-slide image="{{asset('storage/test/2.webp')}}" />
                <x-home.boxes-slide image="{{asset('storage/test/3.webp')}}" />
                <x-home.boxes-slide image="{{asset('storage/test/4.webp')}}" />
            </div>

            <!-- Navigation arrows -->
            <div class="mx-auto mt-6 flex w-[30%] items-center justify-between">
                <div
                    @click="pervSlide()"
                    :class="{ 'text-gray-300 cursor-default': isBeginning, 'cursor-pointer': !isBeginning }"
                    class="duration-4 00 transition-colors"
                >
                    <x-icons.arrow-right class="h-6 w-6 rotate-180" />
                </div>
                <div class="swiper-pagination !static"></div>

                <div
                    @click="nextSlide()"
                    :class="{ 'text-gray-300 cursor-default': isEnd, 'cursor-pointer': !isEnd }"
                    class="duration-4 00 transition-colors"
                >
                    <x-icons.arrow-right class="h-6 w-6" />
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
                            slidesPerView: 3,
                            spaceBetween: 20,
                            loop: false,
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
