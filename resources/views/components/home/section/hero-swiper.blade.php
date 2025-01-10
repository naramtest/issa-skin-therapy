<section class="relative w-full" x-data="swiper()">
    <div class="hero-swiper swiper mt-4 h-[80dvh] w-full lg:h-[97dvh]">
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
                topSectionSwiper: null,
                init() {
                    this.topSectionSwiper = new Swiper('.hero-swiper', {
                        modules: [Autoplay],
                        slidesPerView: 1.069,
                        centeredSlides: true,
                        initialSlide: 2,
                        loop: true,
                        spaceBetween: 25,
                        speed: 1000,
                        // autoplay: {
                        //     delay: 3000,
                        //     disableOnInteraction: false,
                        // },
                        grabCursor: true,
                        on: {
                            slideChange: function () {
                                // Handle video if exists
                                const activeSlide =
                                    this.slides[this.activeIndex];
                                const video =
                                    activeSlide.querySelector('video');
                                if (video) {
                                    video.currentTime = 0;
                                    video.play().catch(() => {
                                        video.muted = true;
                                        video.play();
                                    });
                                }

                                // Reset all slides' content
                                this.slides.forEach((slide) => {
                                    const wrapper = slide.querySelector(
                                        '[data-slide="content-wrapper"]',
                                    );
                                    if (wrapper && wrapper._x_dataStack) {
                                        Alpine.$data(wrapper).isActive = false;
                                    }
                                });

                                // Activate current slide content
                                setTimeout(() => {
                                    const activeWrapper =
                                        activeSlide.querySelector(
                                            '[data-slide="content-wrapper"]',
                                        );
                                    if (
                                        activeWrapper &&
                                        activeWrapper._x_dataStack
                                    ) {
                                        Alpine.$data(activeWrapper).isActive =
                                            true;
                                    }
                                }, 50);
                            },
                        },
                    });
                },
                nextSlide() {
                    this.topSectionSwiper?.slideNext(1000);
                },
                pervSlide() {
                    this.topSectionSwiper?.slidePrev(1000);
                },
            };
        }
    </script>
@endpushonce
