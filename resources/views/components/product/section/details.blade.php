@props([
    /**@var\App\Models\Product$product*/"product",
    "media",
    "type",
])

<x-home.section-container
    class="padding-from-side-menu flex justify-between pb-10 pt-14"
>
    <x-product.product-gallery :media="$media" />
    <div class="flex w-[36%] flex-col">
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
            <span aria-hidden="true" class="rating-star hidden lg:block"></span>
            <p class="ms-2 font-[300] text-darkColor">(15 customer reviews)</p>
        </div>
        <div class="no-tailwind my-5">
            <div class="!ps-10 text-darkColor">
                {!! $product->description !!}
            </div>
        </div>
        {{-- TODO: tabby payment --}}
        <div
            class="flex gap-x-8 rounded-[10px] border-[1px] border-[#D1D5DB] px-5 py-5"
        >
            <div class="flex-1 text-sm text-darkColor">
                <span>4 interest-free payments of</span>
                <strong>AED 55.00</strong>
                <span>. No fees. Shariah-compliant.</span>
                <a class="underline" href="/">Learn more</a>
            </div>
            <img
                class="h-[30px] w-[80px]"
                src="{{ asset("storage/icons/tabby.svg") }}"
                alt="{{ __("store.Tabby") }}"
            />
        </div>
        {{-- TODO:  Remove when Out of Stock --}}
        <x-general.add-to-cart
            type="{{$type}}"
            :product="$product"
            class="my-4 flex items-center"
        >
            {{-- TODO: add to cart --}}
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
            <x-slot:button>
                <x-general.button-black-animation
                    class="ms-4 !flex-1 rounded-3xl !py-2"
                >
                    <span class="z-10">
                        {{ __("store.Add to Card") }}
                    </span>
                </x-general.button-black-animation>
            </x-slot>
        </x-general.add-to-cart>

        {{-- TODO: button animation --}}
        <a href="{{ route("checkout.index") }}" class="">
            <x-general.button-white-animation
                class="border !border-darkColor !py-2"
            >
                <span class="z-10 text-center">
                    {{ __("store.Check Out") }}
                </span>
            </x-general.button-white-animation>
        </a>
        <div class="mt-6 flex justify-between px-2">
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
