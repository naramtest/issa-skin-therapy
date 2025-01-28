@props([
    "title",
    "url",
    "image",
    "subtitle",
])

<div {{ $attributes->class(["md:mt-6 mt-8 flex flex-col md:flex-row "]) }}>
    <div
        style="
            background-image: url({{ $image }});
            background-repeat: no-repeat;
            background-size: cover;
            background-position: center center;
        "
        class="card-hover-trigger full-rounded card-overlay relative h-[300px] rounded-[20px] md:w-[32%] lg:h-[450px] lg:w-[25%]"
    >
        <a
            aria-label="{{ $title }}"
            href=" {{ $url }} "
            class="relative z-10 block h-full w-full"
        >
            <div class="absolute bottom-7 w-full px-7 text-white">
                <div class="flex items-center justify-between">
                    <h3
                        class="text-underline text-underline-white text-xl font-bold"
                    >
                        {{ $title }}
                    </h3>
                    <x-icons.card-arrow-right
                        class="arrow h-5 w-5 text-white rtl:rotate-180"
                    />
                </div>
                <p class="mt-2">{{ $subtitle }}</p>
            </div>
        </a>
    </div>
    <div
        x-data="collectionSwiper()"
        class="mt-6 w-full md:ms-6 md:mt-0 md:w-[67%] lg:w-[74%]"
    >
        <div class="collection-swiper swiper h-full">
            <div class="swiper-wrapper">
                {{ $slot }}
            </div>
        </div>
        <div class="h-full"></div>
    </div>
</div>

@pushonce("scripts")
    <script>
        function collectionSwiper() {
            return {
                collectionSwiper: new Swiper('.collection-swiper', {
                    modules: [Autoplay],
                    slidesPerView: 1,
                    spaceBetween: 10,
                    speed: 800,
                    breakpoints: {
                        // when window width is >= 320px
                        640: {
                            slidesPerView: 2,
                            spaceBetween: 10,
                        },
                        // when window width is >= 480px
                        1150: {
                            slidesPerView: 3,
                            spaceBetween: 10,
                        },
                    },
                    autoplay: {
                        delay: 5000,
                        disableOnInteraction: false,
                    },
                    loop: false,

                    init() {
                        this.collectionSwiper.init();

                        let goingForward = true;

                        this.collectionSwiper.on('slideChange', () => {
                            const lastSlideIndex =
                                this.collectionSwiper.slides.length - 3;

                            if (
                                goingForward &&
                                this.collectionSwiper.activeIndex >=
                                    lastSlideIndex
                            ) {
                                goingForward = false;
                                this.collectionSwiper.params.autoplay.reverseDirection = true;
                            } else if (
                                !goingForward &&
                                this.collectionSwiper.activeIndex === 0
                            ) {
                                goingForward = true;
                                this.collectionSwiper.params.autoplay.reverseDirection = false;
                            }
                        });
                    },
                }),
            };
        }
    </script>
@endpushonce
