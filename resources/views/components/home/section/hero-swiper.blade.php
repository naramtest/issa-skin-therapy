<section class="relative w-full" x-data="swiper()">
    <div class="hero-swiper swiper mt-4 h-[100dvh] w-full">
        <div class="swiper-wrapper h-full w-full">
            <x-home.slider.image-hero-slide
                img-url="{{asset('storage/test/hero1.webp')}}"
            />
            <x-home.slider.image-hero-slide
                img-url="{{asset('storage/test/hero2.webp')}}"
            />

            <x-home.slider.video-hero-slide
                vid="{{asset('storage/video/hoempage3.webm')}}"
            />
            <x-home.slider.image-hero-slide
                img-url="{{asset('storage/test/hero1.webp')}}"
            />
            <x-home.slider.image-hero-slide
                img-url="{{asset('storage/test/hero2.webp')}}"
            />

            <x-home.slider.video-hero-slide
                vid="{{asset('storage/video/hoempage3.webm')}}"
            />
        </div>
    </div>
    <div
        class="absolute bottom-10 z-10 flex w-full translate-y-1/2 items-center justify-between px-24"
    >
        <img
            @click="pervSlide()"
            class="h-[25px] w-[25px] cursor-pointer"
            src="{{ asset("storage/icons/icon-left.svg") }}"
            alt="{{ __("store.Next Arrow") }}"
        />
        <img
            @click="nextSlide()"
            class="h-[25px] w-[25px] cursor-pointer"
            src="{{ asset("storage/icons/icon-right.svg") }}"
            alt="{{ __("store.Previous Arrow") }}"
        />
    </div>
</section>
@pushonce("scripts")
    <script>
        function swiper() {
            return {
                topSectionSwiper: new Swiper('.hero-swiper', {
                    modules: [Autoplay],
                    slidesPerView: 1.1,
                    centeredSlides: true,
                    initialSlide: 2,
                    loop: true,
                    spaceBetween: 40,
                    speed: 1000,
                    autoplay: {
                        delay: 3000,
                    },
                    grabCursor: true,
                }),
                nextSlide() {
                    this.topSectionSwiper.slideNext(1000);
                },
                pervSlide() {
                    this.topSectionSwiper.slidePrev(1000);
                },
            };
        }
    </script>
@endpushonce
