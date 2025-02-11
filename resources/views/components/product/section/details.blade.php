@props([
    /**@var\App\Models\Product$product*/"product",
    "media",
    "type",
])

<x-home.section-container
    class="padding-from-side-menu flex flex-col justify-between gap-y-10 pb-10 pt-14 md:flex-row"
>
    <x-product.product-gallery :media="$media" />
    <div class="flex flex-col md:w-[36%]">
        <p class="text-sm font-[300] leading-[20px] text-darkColor">
            {{ __("store.Patented P.E.T.® Technology") }}
        </p>
        <h2 class="mb-3 mt-3 text-3xl font-bold">{{ $product->name }}</h2>
        <p class="mb-3 text-lg">
            <x-price
                @class(["text-gray-400 line-through" => $product->isOnSale()])
                :money="$product->money_regular_price"
            />

            @if ($product->isOnSale())
                <x-price class="ms-3" :money="$product->money_sale_price" />
            @endif
        </p>
        <div class="flex">
            <span aria-hidden="true" class="rating-star hidden md:block"></span>
            {{-- TODO: review --}}
            <p class="ms-2 font-[300] text-darkColor">(15 customer reviews)</p>
        </div>
        <div class="no-tailwind my-5">
            <div class="!ps-10 text-darkColor">
                {!! $product->description !!}
            </div>
        </div>
        {{-- Tabby Promo Section --}}
        <div class="border-t border-gray-200 pt-4">
            <livewire:checkout.payment-methods.tabby-promo-component
                :price="$product->current_money_price"
                source="product"
                selector="#TabbyProductPromo"
            />
        </div>
        @php
            $outOfStock = ! $product->inventory()->canBePurchased(1);
        @endphp

        <x-general.add-to-cart
            type="{{$type}}"
            :product="$product"
            class="my-4 flex w-full items-center gap-x-2"
        >
            @unless ($outOfStock)
                <label for="quantity">
                    <input
                        x-model="quantity"
                        class="rounded-[50px] border-[1px] border-[#D1D5DB] px-2 py-2 text-center focus-visible:outline-0"
                        type="number"
                        name="quantity"
                        id="quantity"
                        value="1"
                        min="1"
                        max="30"
                    />
                </label>
            @endunless

            <x-slot:button>
                <x-general.button-black-animation
                    :disable="$outOfStock"
                    class="rounded-3xl !py-2"
                >
                    <span
                        class="relative z-10 inline-block flex items-center gap-x-4"
                    >
                        <div
                            x-show="isLoading"
                            class="add-to-cart-loader"
                        ></div>
                        @if ($outOfStock)
                            {{ __("store.Out Of Stock") }}
                        @else
                            {{ __("store.Add to Card") }}
                        @endif
                    </span>
                </x-general.button-black-animation>
            </x-slot>
        </x-general.add-to-cart>

        <a href="{{ route("checkout.index") }}" class="">
            <x-general.button-white-animation
                class="border !border-darkColor !py-2"
            >
                <span class="z-10 text-center">
                    {{ __("store.Check Out") }}
                </span>
            </x-general.button-white-animation>
        </a>
        <div
            class="mt-6 flex flex-col justify-between gap-y-2 px-2 lg:flex-row rtl:text-xs"
        >
            <div class="flex gap-x-2">
                <span>{{ __("store.Social:") }}</span>
                <x-layout.header.home.social
                    width="w-5"
                    height="h-5"
                    color="text-black"
                    class="gap-x-2"
                />
            </div>
            <a href="{{ route("contact.index") }}" class="flex gap-x-2">
                <x-icons.qustion-mark />
                <span>{{ __("store.Need help? Contact us") }}</span>
            </a>
        </div>
    </div>
</x-home.section-container>
