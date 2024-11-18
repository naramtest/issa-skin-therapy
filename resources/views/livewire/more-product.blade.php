<x-home.section-container
    x-data="productSwiper()"
    class="padding-from-side-menu py-12"
    x-init="$wire.on('product-filtered', () => {
          initSwiper()
    })"
>
    <h2 class="headline-font">{{ __("store.You may also like") }}</h2>
    <div class="mt-12 flex items-center justify-between">
        <ul class="flex flex-1 items-center gap-x-4">
            <x-product.categories-item
                id="-1"
                name="Cleansers"
                :is-active="$selectedCategory === -1"
            />
            <x-product.categories-item
                id="1"
                name="Hydrate & Protect"
                :is-active="$selectedCategory === 1"
            />
            <x-product.categories-item
                id="2"
                name="Treat"
                :is-active="$selectedCategory === 2"
            />
            <x-product.categories-item
                id="3"
                name="The Collections"
                :is-active="$selectedCategory === 3"
            />
        </ul>
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
    <div
        wire:loading.class="opacity-0 invisible translate-y-4 h-0"
        wire:loading.remove.class="opacity-100 visible translate-y-0 h-auto"
        class="product-swiper swiper mt-8 transition-all duration-500 ease-in-out"
    >
        <div class="swiper-wrapper">
            @foreach ($this->products as $product)
                {{-- TODO: price and sale , type , category , img --}}
                <x-general.product-item-card
                    :product="$product"
                    class="swiper-slide !h-auto"
                    subtitle="A-luminate, CLEANSE"
                />
            @endforeach
        </div>
    </div>
    <div
        wire:loading.class="opacity-100 !block  -translate-y-4 h-auto"
        wire:loading.remove.class="opacity-0   translate-y-4 h-0"
        class="hidden w-full transform transition-all duration-500 ease-out"
    >
        <div class="flex h-[450px] w-full items-center justify-center">
            <span class="loader"></span>
            <style>
                .loader {
                    width: 48px;
                    height: 48px;
                    border: 2px dashed var(--color-dark);
                    border-radius: 50%;
                    display: inline-block;
                    position: relative;
                    box-sizing: border-box;
                    animation: rotation 1s linear infinite;
                }

                .loader::after {
                    content: '';
                    box-sizing: border-box;
                    position: absolute;
                    left: 50%;
                    top: 50%;
                    transform: translate(-50%, -50%);
                    width: 40px;
                    height: 40px;
                    border-radius: 50%;
                    border: 2px solid transparent;
                    border-bottom-color: var(--color-dark);
                }

                @keyframes rotation {
                    0% {
                        transform: rotate(0deg);
                    }
                    100% {
                        transform: rotate(360deg);
                    }
                }
            </style>
        </div>
    </div>

    @pushonce("scripts")
        <script>
            function productSwiper() {
                return {
                    productSwiper: null,
                    show: true,
                    selectCategory(id) {
                        Livewire.dispatch('post-created', {
                            selectedCategory: id,
                        });
                    },
                    initSwiper() {
                        if (this.productSwiper) {
                            this.productSwiper.destroy();
                        }

                        this.productSwiper = new Swiper('.product-swiper', {
                            slidesPerView: 4,
                            loop: true,
                            spaceBetween: 20,
                        });
                    },

                    init() {
                        this.initSwiper();
                    },

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
