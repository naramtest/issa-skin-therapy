@props(['product'])
<x-home.section-container
    class="relative flex w-full flex-col items-center gap-16 px-40 py-20 lg:flex-row"
>
    <x-general.product-gallery class="lg:w-[55%]" :media="$product->media" />
    <div class="flex w-full flex-col lg:w-[45%]">
        <p class="font-[300] leading-[20px] text-darkColor">Our Best Seller</p>
        <h2 class="mb-3 mt-3 text-4xl font-bold">{{$product->name}}</h2>
        <p class="mb-3 text-lg">
            <x-price
                @class(["text-gray-400 line-through" => $product->isOnSale()])
                :money="$product->money_regular_price"
            />

            @if ($product->isOnSale())
                <x-price class="ms-3" :money="$product->money_sale_price" />
            @endif</p>
        <div class="flex">
            <span aria-hidden="true" class="rating-star hidden lg:block"></span>
            <p class="ms-2 font-[300] text-darkColor">(15 customer reviews)</p>
        </div>
        <div class="no-tailwind my-5">
            <div class="!ps-10 text-darkColor">
                {!! $product->short_description !!}
            </div>
        </div>
        <button
            class="w-full mt-4"

        >
            <x-general.button-black-animation class="!py-2">
                 <span class="relative z-10 inline-block ">
                            {{__('store.Add to Card')}}
                    </span>
            </x-general.button-black-animation>

        </button>

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
            <div class="flex gap-x-2">
                <x-icons.qustion-mark />
                <span>{{ __("store.Need help?") }}</span>
                <a class="hover:scale-105 transition-transform duration-300"
                   href="{{route('contact.index')}}">{{ __('store.Contact us') }}</a>
            </div>
        </div>

        <a
            href="{{route('product.show', $product)}}"
            class="mt-6 flex items-center justify-between border-t-[1px] border-[#A5BBC4] pt-6"
        >
            <p class="text-sm font-semibold">
                {{ __("store.View full details") }}
            </p>
            <x-icons.arrow-right class="h-5 w-5" />
        </a>
    </div>

</x-home.section-container>
