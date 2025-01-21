@props([
    "media",
])

<div {{ $attributes->class(["w-full md:w-[60%]"]) }}>
    <div
        class="flex w-full flex-col items-center gap-x-12 gap-y-3 md:flex-row"
        x-data="productGallerySwiper()"
    >
        <div class="relative h-full w-full md:w-[60%]">
            <div class="product-gallery-swiper swiper">
                <div class="swiper-wrapper h-full" id="gallery">
                    @foreach ($media as $image)
                        <div
                            class="swiper-slide aspect-square min-h-[600px] overflow-hidden rounded-xl bg-gray-100"
                        >
                            {{-- TODO:save those in the image table as custom attributes --}}
                            @php
                                [$width, $height] = getimagesize($image->getPath());
                            @endphp

                            <a
                                href="{{ $image->getAvailableUrl([config("const.media.optimized")]) }}"
                                data-pswp-width="{{ $width }}"
                                data-pswp-height="{{ $height }}"
                            >
                                {!! \App\Helpers\Media\ImageGetter::responsiveImgElement($image, class: "h-full w-full object-cover") !!}
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
                :disabled="activeIndex === {{ count($media) }} - 1"
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

        <div class="grid grid-cols-3 gap-3 md:flex md:w-[30%] md:flex-col">
            @foreach ($media as $image)
                <button
                    x-show="activeIndex !== {{ $loop->index }}"
                    @click="slideTo({{ $loop->index }})"
                    class="overflow-hidden rounded-lg transition-all duration-300"
                >
                    {!! \App\Helpers\Media\ImageGetter::responsiveImgElement($image, config("const.media.thumbnail"), class: "md:h-[250px] h-[180px] w-full object-cover") !!}
                </button>
            @endforeach
        </div>
    </div>
</div>

@pushonce("scripts")
    <script>
        function productGallerySwiper() {
            return {
                activeIndex: 0,
                productGallerySwiper1: null,
                init() {
                    this.productGallerySwiper1 = new Swiper(
                        '.product-gallery-swiper',
                        {
                            modules: [EffectFade],
                            slidesPerView: 1,
                            speed: 800,
                            effect: 'fade',
                            on: {
                                slideChange: () => {
                                    if (this.productGallerySwiper1) {
                                        this.activeIndex =
                                            this.productGallerySwiper1.realIndex;
                                    }
                                },
                            },
                        },
                    );
                },
                slideTo(index) {
                    this.productGallerySwiper1?.slideTo(index, 500);
                },
                nextSlide() {
                    this.productGallerySwiper1?.slideNext(500);
                },
                pervSlide() {
                    this.productGallerySwiper1?.slidePrev(500);
                },
            };
        }
    </script>
@endpushonce
