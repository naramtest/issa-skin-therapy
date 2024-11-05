<div {{ $attributes->class(["w-[60%]"]) }}>
    @php
        $images = [
            asset("storage/test/product/product.webp"),
            asset("storage/test/product/1.webp"),
            asset("storage/test/product/2.webp"),
            asset("storage/test/product/3.webp"),
        ];
    @endphp

    <div class="flex w-full items-center gap-12" x-data="productSwiper()">
        <div class="relative h-full w-[60%]">
            <div class="product-swiper swiper">
                <div class="swiper-wrapper h-full" id="gallery">
                    @foreach ($images as $image)
                        <div
                            class="swiper-slide aspect-square min-h-[600px] overflow-hidden rounded-xl bg-gray-100"
                        >
                            {{-- TODO: make the width and hieght dynamic --}}

                            <a
                                href="{{ $image }}"
                                data-pswp-width="2600"
                                data-pswp-height="1600"
                            >
                                <img
                                    src="{{ $image }}"
                                    class="h-full w-full object-cover"
                                    alt=""
                                />
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Navigation Buttons -->
            <button
                @click="pervSlide()"
                class="absolute left-0 top-1/2 z-10 ms-4 -translate-y-1/2 rounded-full bg-white/80 p-2 shadow-lg transition-colors hover:bg-white disabled:opacity-50"
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
                class="absolute end-0 top-1/2 z-10 mr-4 -translate-y-1/2 rounded-full bg-white/80 p-2 shadow-lg transition-colors hover:bg-white disabled:opacity-50"
                :disabled="activeIndex === {{ count($images) }} - 1"
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
        <!-- Thumbnails Column -->

        {{-- TODO: add animation when selecting an image from thumbnails --}}
        <div class="flex w-[30%] flex-col gap-3">
            @foreach ($images as $image)
                <button
                    x-show="activeIndex !== {{ $loop->index }}"
                    @click="slideTo({{ $loop->index }})"
                    class="overflow-hidden rounded-lg transition-all duration-300"
                >
                    <img
                        src="{{ $image }}"
                        class="h-[250px] w-full object-cover"
                        alt=""
                    />
                </button>
            @endforeach
        </div>
    </div>
</div>

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
