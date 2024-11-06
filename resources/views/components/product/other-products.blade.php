<x-home.section-container
    x-data="productSwiper()"
    class="padding-from-side-menu py-12"
>
    <h2 class="headline-font">{{ __("store.You may also like") }}</h2>
    <div class="mt-12 flex items-center justify-between">
        <div class="swiper category flex-1">
            <ul class="swiper-wrapper w-full">
                <x-product.categories-item
                    id="-1"
                    name="Cleansers"
                    :is-active="true"
                />
                <x-product.categories-item
                    id="1"
                    name="Hydrate & Protect"
                    :is-active="false"
                />
                <x-product.categories-item
                    id="2"
                    name="Treat"
                    :is-active="false"
                />
                <x-product.categories-item
                    id="3"
                    name="The Collections"
                    :is-active="false"
                />
            </ul>
        </div>
        <div class="flex items-center">
            <div
                @click=" pervSlide()"
                class="cursor-pointer rounded-full border border-darkColor px-4 py-3 transition-colors duration-200 hover:border-transparent hover:bg-gray-100"
            >
                <img
                    src="{{ asset("storage/icons/small-arrow-left.svg") }}"
                    alt="{{ __("store.Arrow Left") }}"
                />
            </div>
            <div
                @click="nextSlide()"
                class="ms-3 cursor-pointer rounded-full border border-darkColor px-4 py-3 transition-colors duration-200 hover:border-transparent hover:bg-gray-100"
            >
                <img
                    src="{{ asset("storage/icons/small-arrow-right.svg") }}"
                    alt="{{ __("store.Arrow Right") }}"
                />
            </div>
        </div>
    </div>
    <div class="product-swiper swiper mt-8">
        <div class="swiper-wrapper">
            <x-general.product-item-card
                class="swiper-slide"
                title="LumiCleanse Cleanser"
                img="{{asset('storage/test/product/product.webp')}}"
                subtitle="A-luminate, CLEANSE"
                price="200.00$"
            />

            <x-general.product-item-card
                class="swiper-slide"
                title="LumiCleanse Cleanser"
                img="{{asset('storage/test/product/product.webp')}}"
                subtitle="A-luminate, CLEANSE"
                price="200.00$"
            />
            <x-general.product-item-card
                class="swiper-slide"
                title="LumiCleanse Cleanser"
                img="{{asset('storage/test/product/product.webp')}}"
                subtitle="A-luminate, CLEANSE"
                price="200.00$"
            />
            <x-general.product-item-card
                class="swiper-slide"
                title="LumiCleanse Cleanser"
                img="{{asset('storage/test/product/product.webp')}}"
                subtitle="A-luminate, CLEANSE"
                price="200.00$"
            />
            <x-general.product-item-card
                class="swiper-slide"
                title="LumiCleanse Cleanser"
                img="{{asset('storage/test/product/product.webp')}}"
                subtitle="A-luminate, CLEANSE"
                price="200.00$"
            />
            <x-general.product-item-card
                class="swiper-slide"
                title="LumiCleanse Cleanser"
                img="{{asset('storage/test/product/product.webp')}}"
                subtitle="A-luminate, CLEANSE"
                price="200.00$"
            />
            <x-general.product-item-card
                class="swiper-slide"
                title="LumiCleanse Cleanser"
                img="{{asset('storage/test/product/product.webp')}}"
                subtitle="A-luminate, CLEANSE"
                price="200.00$"
            />
            <x-general.product-item-card
                class="swiper-slide"
                title="LumiCleanse Cleanser"
                img="{{asset('storage/test/product/product.webp')}}"
                subtitle="A-luminate, CLEANSE"
                price="200.00$"
            />
        </div>
    </div>

    @pushonce("scripts")
        <script>
            function productSwiper() {
                return {
                    productSwiper: new Swiper('.product-swiper', {
                        slidesPerView: 4,
                        loop: true,
                        spaceBetween: 20,
                    }),

                    categorySwiper: new Swiper('.category', {
                        slidesPerView: 'auto',
                        spaceBetween: 30,
                    }),
                    nextSlide() {
                        this.productSwiper?.slideNext(1000);
                    },
                    pervSlide() {
                        this.productSwiper?.slidePrev(1000);
                    },
                };
            }
        </script>
    @endpushonce
</x-home.section-container>
