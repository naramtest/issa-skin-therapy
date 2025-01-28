@props([
    "media",
])

<div
    {{ $attributes->class(["flex flex-col-reverse items-center md:flex-row w-full gap-4 "]) }}
    x-data="productSwiper()"
>
    <!-- Thumbnails Column -->
    <div class="grid w-full grid-cols-3 gap-3 md:flex md:flex-col lg:w-[120px]">
        @foreach ($media as $image)
            <button
                x-show="activeIndex !== {{ $loop->index }}"
                @click="slideTo({{ $loop->index }})"
                class="overflow-hidden rounded-lg transition-all duration-300"
            >
                {!! \App\Helpers\Media\ImageGetter::responsiveImgElement($image, config("const.media.thumbnail"), class: "h-[180px]  w-full object-cover") !!}
            </button>
        @endforeach
    </div>

    <!-- Main Image -->
    <div class="relative w-[calc(100%-170px)] lg:w-[calc(100%-150px)]">
        <div class="product-swiper swiper">
            <div class="swiper-wrapper h-full" id="gallery">
                @foreach ($media as $image)
                    <div
                        class="swiper-slide aspect-square min-h-[400px] overflow-hidden rounded-xl bg-gray-100 lg:min-h-[600px]"
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
            class="absolute start-0 top-1/2 z-10 me-4 -translate-y-1/2 rounded-full bg-white/80 p-2 shadow-lg transition-colors hover:bg-white disabled:opacity-50 rtl:rotate-180"
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
            class="absolute end-0 top-1/2 z-10 ms-4 -translate-y-1/2 rounded-full bg-white/80 p-2 shadow-lg transition-colors hover:bg-white disabled:opacity-50 rtl:rotate-180"
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
