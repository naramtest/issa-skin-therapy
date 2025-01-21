{{-- TODO: add this to dashboard for dynimc testimonials --}}

<x-home.section-container
    style="
                background-image: url({{asset('storage/images/10.webp')}});
                background-position: center center;
                background-repeat: no-repeat;
                background-size: cover;
            "
    class="card-overlay relative mt-10 flex h-[450px] items-center justify-center overflow-hidden lg:h-[600px]"
>
    <div class="z-10 mx-auto w-full px-4 lg:w-[60%] lg:px-0">
        <div x-data="testimonialSwiper()" class="testimonials-swiper swiper">
            <div class="swiper-wrapper">
                <x-home.testimonials-item class="swiper-slide" />
                <x-home.testimonials-item class="swiper-slide" />
                <x-home.testimonials-item class="swiper-slide" />
            </div>
        </div>
    </div>

    @pushonce("scripts")
        <script>
            function testimonialSwiper() {
                return {
                    testimonialSwiper: new Swiper('.testimonials-swiper', {
                        modules: [Autoplay],
                        slidesPerView: 1,
                        loop: true,
                        grabCursor: true,
                        spaceBetween: 40,
                        speed: 1000,
                        autoplay: {
                            delay: 3000,
                            disableOnInteraction: false,
                        },
                    }),
                };
            }
        </script>
    @endpushonce
</x-home.section-container>
