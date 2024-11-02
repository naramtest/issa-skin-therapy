<div {{ $attributes->class(["mt-6 flex px-6"]) }}>
    <div
        style="
            background-image: url({{ asset("storage/test/hero2.webp") }});
            background-repeat: no-repeat;
            background-size: cover;
            background-position: center center;
        "
        class="card-hover-trigger relative h-[450px] w-[25%] rounded-[20px]"
    >
        <a
            aria-label="{{ __("store.Shop by collection") }}"
            href=""
            class="relative z-10 block h-full w-full"
        >
            <div class="absolute bottom-7 w-full px-7 text-white">
                <div class="flex items-center justify-between">
                    <h3
                        class="text-underline text-underline-white text-xl font-bold"
                    >
                        {{ __("store.Shop by collection") }}
                    </h3>
                    <x-icons.card-arrow-right
                        class="arrow h-5 w-5 text-white"
                    />
                </div>
                <p class="mt-2">{{ __("store.Check out all") }}</p>
            </div>
        </a>
    </div>
    <div x-data="collectionSwiper()" class="ms-6 w-[74%]">
        <div class="collection-swiper swiper h-full">
            <div class="swiper-wrapper">
                <x-home.collection-swiper-slide />
                <x-home.collection-swiper-slide />
                <x-home.collection-swiper-slide />
                <x-home.collection-swiper-slide />
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
                    slidesPerView: 3,
                    spaceBetween: 10,
                    speed: 800,
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
