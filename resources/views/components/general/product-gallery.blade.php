@php
    $imageCount = 4;
@endphp

<div
    {{ $attributes->class(["flex w-full gap-4 "]) }}
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
                    alt=""
                />
            </button>
        @endfor
    </div>

    <!-- Main Image -->
    <div class="relative w-[calc(100%-120px)]">
        <div class="product-swiper swiper">
            <div class="swiper-wrapper h-full" id="gallery">
                @for ($i = 0 ; $i < 4 ; $i++)
                    <div
                        class="swiper-slide aspect-square h-full overflow-hidden rounded-xl bg-gray-100"
                    >
                        {{-- TODO: make the width and hieght dynamic --}}
                        <a
                            href="{{ asset("storage/test/" . ($i + 1) . ".webp") }}"
                            data-pswp-width="2600"
                            data-pswp-height="1600"
                        >
                            <img
                                src="{{ asset("storage/test/" . ($i + 1) . ".webp") }}"
                                class="h-full w-full object-cover"
                                alt=""
                            />
                        </a>
                    </div>
                @endfor
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
