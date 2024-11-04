<x-home.section-container
    style="
                background-image: url({{asset('storage/images/10.webp')}});
                background-position: center center;
                background-repeat: no-repeat;
                background-size: cover;
            "
    class="card-overlay relative mt-10 flex h-[600px] items-center justify-center overflow-hidden"
>
    <div class="z-10 mx-auto w-[60%]">
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
                        slidesPerView: 1,
                        loop: true,
                        grabCursor: true,
                        spaceBetween: 40,
                    }),
                };
            }
        </script>
    @endpushonce
</x-home.section-container>
