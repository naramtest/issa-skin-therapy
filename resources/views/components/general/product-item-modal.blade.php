@props([
    /**@var\mixed*/"product",
])

<template x-teleport="body">
    <div
        x-show="isModalOpen"
        x-transition:enter="transition duration-500 ease-out"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition duration-200 ease-in"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-50 overflow-y-auto"
        @click.self="isModalOpen = false"
    >
        <!-- Modal Backdrop -->
        <div class="fixed inset-0 bg-black/50"></div>

        <!-- Modal Content -->
        <div
            class="relative flex min-h-screen items-center justify-center px-2 py-4 lg:px-4"
        >
            <div
                @click.outside="isModalOpen = false"
                class="relative w-full overflow-hidden rounded-2xl bg-white px-4 py-20 shadow-xl lg:max-w-4xl lg:px-12"
            >
                <!-- Close Button -->
                <button
                    @click="isModalOpen = false"
                    class="absolute right-4 top-4 rounded-full border p-3 text-gray-400 hover:text-gray-600"
                >
                    <svg
                        class="h-6 w-6"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M6 18L18 6M6 6l12 12"
                        ></path>
                    </svg>
                </button>

                <!-- Modal Body -->
                <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                    <!-- Product Image -->
                    <div class="relative">
                        {!! \App\Helpers\Media\ImageGetter::responsiveFeaturedImg($product, class: "h-[400px] w-full rounded-lg object-cover") !!}
                    </div>

                    <!-- Product Details -->
                    <div class="py-6">
                        <p class="text-xs text-[#8C92A4]">
                            @if (count($product->categories))
                                <span>
                                    <a
                                        href="{{ route("product.category", ["slug" => $product->categories[0]->slug]) }}"
                                    >
                                        {{ $product->categories[0]->name }}
                                    </a>
                                </span>
                            @endif

                            @if (count($product->types))
                                <span>
                                    <a
                                        href=" {{ route("product.category", ["slug" => $product->types[0]->slug]) }}"
                                    >
                                        {{ $product->types[0]->name }}
                                    </a>
                                </span>
                            @endif
                        </p>
                        <h2 class="mt-2 text-2xl font-semibold">
                            {{ $product->name }}
                        </h2>
                        <p class="mb-3 text-lg">
                            <x-price
                                @class(["text-gray-400 line-through" => $product->isOnSale()])
                                :money="$product->money_regular_price"
                            />

                            @if ($product->isOnSale())
                                <x-price
                                    class="ms-3"
                                    :money="$product->money_sale_price"
                                />
                            @endif
                        </p>

                        <div class="no-tailwind my-5">
                            <div class="!ps-10 text-darkColor">
                                {!! $product->description !!}
                            </div>
                        </div>

                        <div
                            class="flex items-center justify-between gap-x-2 text-sm lg:gap-x-5"
                        >
                            <x-general.add-to-cart
                                :product="$product"
                                :type="\App\Enums\ProductType::PRODUCT->value"
                                class="flex flex-1 items-center justify-between gap-x-2 text-sm lg:gap-x-5"
                            >
                                <label for="quantity">
                                    <input
                                        class="rounded-[50px] bg-[#F4F4F4] px-2 py-2 text-center focus-visible:outline-0"
                                        type="number"
                                        name="quantity"
                                        id="quantity"
                                        value="1"
                                        min="1"
                                        max="30"
                                    />
                                </label>
                                <x-slot:button>
                                    <x-general.button-black-animation
                                        @click="isModalOpen = false"
                                        class="text-nowrap !py-2 px-3 lg:!px-7"
                                    >
                                        <span class="z-10">
                                            {{ __("store.Add to cart") }}
                                        </span>
                                    </x-general.button-black-animation>
                                </x-slot>
                            </x-general.add-to-cart>
                            <a href="{{ route("checkout.index") }}">
                                <x-general.button-white-animation
                                    class="text-nowrap !border !border-black !py-2 px-3 lg:!px-7"
                                >
                                    <span class="z-10">
                                        {{ __("store.Check Out") }}
                                    </span>
                                </x-general.button-white-animation>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
