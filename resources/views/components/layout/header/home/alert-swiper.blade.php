<div
    x-data="alertSwiper()"
    class="flex w-full flex-nowrap items-center md:hidden lg:flex lg:w-[50%] lg:gap-x-4 xl:gap-x-6"
>
    <img
        @click="nextSlide()"
        class="hidden h-6 w-6 cursor-pointer md:block"
        src="{{ asset("storage/icons/icon-left.svg") }}"
        alt="{{ __("store.arrow") }}"
    />
    <div class="swiper alert-swiper">
        <div class="swiper-wrapper">
            <x-layout.header.home.alert-swiper-item />
            <x-layout.header.home.alert-swiper-item />
            <x-layout.header.home.alert-swiper-item />
        </div>
    </div>
    <img
        @click="pervSlide()"
        class="hidden h-6 w-6 cursor-pointer md:block"
        src="{{ asset("storage/icons/icon-right.svg") }}"
        alt="{{ __("store.arrow") }}"
    />
</div>

@pushonce("scripts")
    <script>
        function alertSwiper() {
            return {
                alertSwiper1: null,
                init() {
                    this.alertSwiper1 = new Swiper('.alert-swiper', {
                        modules: [Autoplay],
                        slidesPerView: 1,
                        speed: 800,
                        loop: true,
                        spaceBetween: 30,
                        autoplay: {
                            delay: 3000,
                            disableOnInteraction: false,
                        },
                    });
                },
                nextSlide() {
                    this.alertSwiper1?.slideNext(1000);
                },
                pervSlide() {
                    this.alertSwiper1?.slidePrev(1000);
                },
            };
        }
    </script>
@endpushonce
