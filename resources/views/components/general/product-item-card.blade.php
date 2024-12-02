@props([
    "subtitle",
    "product",
])

<div
    href="/"
    wire:key="{{ $product->id }}"
    x-data="{ isModalOpen: false }"
    {{ $attributes->class(["block !flex flex-col rounded-[15px] bg-[#F4F4F4] p-2"]) }}
>
    <div class="group relative">
        <a href="{{ route("product.show", ["product" => $product->slug]) }}">
            {!! \App\Helpers\Media\ImageGetter::responsiveFeaturedImg($product, class: "h-[400px] w-full rounded-[10px] object-cover") !!}
        </a>
        <button
            @click="isModalOpen = true"
            class="absolute end-3 top-4 rounded-full border p-3 transition-colors hover:bg-white/80"
        >
            <x-gmdi-visibility-o class="h-5 w-5 text-gray-500" />
        </button>
        <button
            @click="Livewire.dispatch('add-to-cart' , { product: {{ $product->id }} , quantity : 1 })"
            class="absolute bottom-16 start-1/2 -translate-x-1/2 translate-y-full scale-0 rounded-[50px] bg-darkColor px-5 py-2 text-sm text-white opacity-0 transition-all duration-500 group-hover:translate-y-0 group-hover:scale-100 group-hover:opacity-100"
            href="/"
        >
            {{ __("store.Add to cart") }}
        </button>
    </div>
    <div class="h-full px-2 pb-3 pt-5">
        <div class="flex h-full flex-col justify-between">
            <p class="text-xs text-[#8C92A4]">
                @if (count($product->categories))
                    <span class="hover:text-gray-500">
                        <a
                            href="{{ route("product.category", ["slug" => $product->categories[0]->slug]) }}"
                        >
                            {{ $product->categories[0]->name }}
                        </a>
                    </span>
                @endif

                {{-- TODO: add type pages --}}
                @if (count($product->types))
                    <span>-</span>
                    <span>
                        <a
                            href=" {{ route("product.category", ["slug" => $product->types[0]->slug]) }}"
                        >
                            {{ $product->types[0]->name }}
                        </a>
                    </span>
                @endif
            </p>

            <a
                href="{{ route("product.show", ["product" => $product->slug]) }}"
                class="mt-3 flex items-end justify-between gap-x-2 text-darkColor"
            >
                <h3 class="flex-1 text-[17px] font-semibold">
                    {{ $product->name }}
                </h3>
                <p @class([" text-center" => $product->isOnSale()])>
                    <x-price
                        @class(["text-[14px]" => ! $product->isOnSale(), "text-xs text-gray-400 line-through" => $product->isOnSale()])
                        :money="$product->money_regular_price"
                    />

                    @if ($product->isOnSale())
                        <x-price
                            class="block text-[14px]"
                            :money="$product->money_sale_price"
                        />
                    @endif
                </p>
            </a>
        </div>
    </div>

    <!-- Quick View Modal -->
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
                class="relative flex min-h-screen items-center justify-center p-4"
            >
                <div
                    @click.outside="isModalOpen = false"
                    class="relative w-full max-w-4xl overflow-hidden rounded-2xl bg-white px-12 py-20 shadow-xl"
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
                    <div class="grid grid-cols-2 gap-6">
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
                                class="flex items-center justify-between gap-x-5 text-sm"
                            >
                                <x-general.add-to-cart
                                    :product="$product"
                                    class="flex flex-1 items-center justify-between gap-x-5 text-sm"
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
                                            class="text-nowrap !px-7 !py-2"
                                        >
                                            <span class="z-10">
                                                {{ __("store.Add to cart") }}
                                            </span>
                                        </x-general.button-black-animation>
                                    </x-slot>
                                </x-general.add-to-cart>
                                <a href="{{ route("checkout.index") }}">
                                    <x-general.button-white-animation
                                        class="text-nowrap !border !border-black !px-7 !py-2"
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
</div>
