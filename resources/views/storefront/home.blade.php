<x-store-main-layout>
    <main class="relative">
        <x-home.section.hero-swiper />
        <x-home.section.collection />
        @php
            $imageCount = 4;
        @endphp

        <section
            class="relative flex w-full flex-col items-start gap-5 px-40 lg:flex-row"
        >
            <div
                class="flex w-full gap-4 lg:w-[55%]"
                x-data="productSwiper()"
            >
                <!-- Thumbnails Column -->
                <div class="flex w-[100px] flex-col gap-3">
                    @for ($i = 0 ; $i < $imageCount ; $i++)
                        <button
                            @click="slideTo({{ $i }})"
                            :class="activeIndex === {{ $i }} ? 'border-black shadow-lg' : 'border-transparent hover:border-gray-300'"
                            class="overflow-hidden rounded-lg border-2 transition-all duration-300"
                        >
                            <img
                                src="{{ asset("storage/test/" . ($i + 1) . ".webp") }}"
                                class="h-24 w-full object-cover"
                            />
                        </button>
                    @endfor
                </div>

                <!-- Main Image -->
                <div class="relative w-[calc(100%-120px)]">
                    <div class="product-swiper swiper">
                        <div class="swiper-wrapper h-full">
                            @for ($i = 0 ; $i < 4 ; $i++)
                                <div
                                    class="swiper-slide aspect-square h-full overflow-hidden rounded-xl bg-gray-100"
                                >
                                    <img
                                        src="{{ asset("storage/test/" . ($i + 1) . ".webp") }}"
                                        class="h-full w-full object-cover"
                                    />
                                </div>
                            @endfor
                        </div>
                    </div>

                    <!-- Navigation Buttons -->
                    <div
                        class="absolute inset-y-0 left-0 right-0 z-10 flex items-center justify-between"
                    >
                        <button
                            @click="pervSlide()"
                            class="ml-4 rounded-full bg-white/80 p-2 shadow-lg transition-colors hover:bg-white disabled:opacity-50"
                            :disabled="activeIndex === 0"
                        >
                            <svg
                                class="h-6 w-6"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M15 19l-7-7 7-7"
                                />
                            </svg>
                        </button>

                        <button
                            @click="nextSlide()"
                            class="mr-4 rounded-full bg-white/80 p-2 shadow-lg transition-colors hover:bg-white disabled:opacity-50"
                            :disabled="activeIndex === {{ $imageCount }} - 1"
                        >
                            <svg
                                class="h-6 w-6"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M9 5l7 7-7 7"
                                />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
            <div class="w-full lg:w-[45%]"></div>
        </section>
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
