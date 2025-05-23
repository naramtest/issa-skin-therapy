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
            {!! \App\Helpers\Media\ImageGetter::responsiveFeaturedImg($product, class: "h-[200px] lg:h-[400px] w-full rounded-[10px] object-cover") !!}
        </a>
        <button
            @click="isModalOpen = true"
            class="absolute end-3 top-4 rounded-full border p-3 transition-colors hover:bg-white/80"
            aria-label="{{ __("store.View details") }}"
        >
            <x-gmdi-visibility-o class="h-5 w-5 text-gray-500" />
        </button>
        @php
            $outOfStock = ! $product->inventory()->canBePurchased(1);
        @endphp

        <x-general.add-to-cart
            :type="\App\Enums\ProductType::PRODUCT->value"
            :product="$product"
            :out-of-stock="$outOfStock"
            @class([
                "absolute bottom-16 start-1/2 -translate-x-1/2 translate-y-full scale-0 rounded-[50px] px-5 py-2 text-sm text-white opacity-0 transition-all duration-500 group-hover:translate-y-0 group-hover:scale-100 group-hover:opacity-100 rtl:translate-x-1/2",
                "bg-darkColor" => ! $outOfStock,
                "bg-[#6c6c6c]" => $outOfStock,
            ])
        >
            <x-slot:button>
                <span x-show="!isLoading">
                    @if ($outOfStock)
                        {{ __("store.Out Of Stock") }}
                    @else
                        {{ __("store.Add to cart") }}
                    @endif
                </span>
                <div class="px-6">
                    <div
                        x-show="isLoading"
                        class="add-to-cart-loader w-"
                    ></div>
                </div>
            </x-slot>
        </x-general.add-to-cart>
    </div>
    <div class="h-full px-2 pb-3 pt-5">
        <div class="flex h-full flex-col justify-between">
            <p class="text-xs text-[#37383d]">
                @if (count($product->categories))
                    <span class="transition-colors hover:text-black">
                        <a
                            href="{{ route("product.category", ["slug" => $product->categories[0]->slug]) }}"
                        >
                            {{ $product->categories[0]->name }}
                        </a>
                    </span>
                @endif

                @if (count($product->types))
                    <span>-</span>
                    <span>
                        <a
                            class="transition-colors hover:text-black"
                            href=" {{ route("product.category", ["slug" => $product->types[0]->slug]) }}"
                        >
                            {{ $product->types[0]->name }}
                        </a>
                    </span>
                @endif
            </p>

            <a
                href="{{ route("product.show", ["product" => $product->slug]) }}"
                class="mt-3 flex flex-col justify-between gap-x-2 text-darkColor lg:flex-row lg:items-end"
            >
                <h3 class="flex-1 text-[15px] font-semibold lg:text-[17px]">
                    {{ $product->name }}
                </h3>
                <p @class([" lg:text-center" => $product->isOnSale()])>
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
    <x-general.product-item-modal :product="$product" />
</div>
