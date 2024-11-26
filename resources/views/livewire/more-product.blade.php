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
            {{-- TODO: Cache Porduct Categories --}}
            @foreach (\App\Models\Category::all() as $category)
                <x-product.categories-item
                    id="{{$category->id}}"
                    name="{{$category->name}}"
                    :is-active="$selectedCategory === $category->id"
                />
            @endforeach

            <x-product.categories-item
                id="-1"
                name="The Collections"
                :is-active="$selectedCategory === -1"
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
            @php
                $products = $this->products;
                $productsCount = $products->count();
                $repetitions = $productsCount < 5 ? ceil(5 / $productsCount) : 1;
            @endphp

            @if ($isProducts)
                @for ($i = 0; $i < $repetitions; $i++)
                    @foreach ($this->products as $product)
                        <x-general.product-item-card
                            :product="$product"
                            class="swiper-slide !h-auto"
                        />
                    @endforeach
                @endfor
            @else
                @for ($i = 0; $i < $repetitions; $i++)
                    @foreach ($this->products as $product)
                        <x-shop.shop-collection
                            :bundle="$product"
                            class="swiper-slide !h-auto"
                            subtitle="For Anti-Wrinkles and Anti-Aging"
                            img="{{asset('storage/test/collection4.webp')}}"
                        />
                    @endforeach
                @endfor
            @endif
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
                        Livewire.dispatch('select-category', {
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
