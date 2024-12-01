@props([
    "bundles",
    "categories",
])
<x-home.section-container class="content-x-padding mt-10 py-14">
    <div class="flex items-center justify-between px-14">
        <div class="w-[25%]">
            <x-home.fancy-heading />
            <a
                href="{{ route("about.index") }}"
                class="mt-10 inline-block w-[200px]"
            >
                <x-general.button-white-animation class="!border-black">
                    <span class="relative z-10 inline-block">
                        {{ __("store.Our Story") }}
                    </span>
                </x-general.button-white-animation>
            </a>
        </div>
        <ol class="w-[60%] text-2xl">
            <li class="mb-1">
                {{ __("store.1. Crafted by Dr") }}
            </li>
            <li class="mb-1">
                {{ __("store.Revolutionary Patent Delivery Technology") }}
            </li>
            <li>{{ __("store.Premium Quality, Made in USA.") }}</li>
        </ol>
    </div>
    <x-home.collection-row
        title="{{__('store.Shop by collection')}}"
        subtitle="{{__('store.Check out all')}}"
        image="{{asset('storage/images/bundle-home-collection.webp')}}"
        url="{{route('bundles.index')}}"
    >
        @foreach ($bundles as $bundle)
            <x-home.collection-swiper-slide
                title="{{$bundle->name}}"
                url="{{route('product.bundle',$bundle)}}"
                :media="$bundle->getFirstMedia(config('const.media.featured'))"
                subtitle="{!! $bundle->subtitle !!}"
            />
        @endforeach
    </x-home.collection-row>

    <x-home.collection-row
        title="{{ __('store.All Products') }}"
        subtitle="{{__('store.Check out all our products')}}"
        image="{{asset('storage/images/product-home-collection.webp')}}"
        url="{{route('bundles.index')}}"
    >
        {{-- TODO: edit images to transparent bg --}}
        @foreach ($categories as $category)
            @unless (Str::contains($category->name, "&"))
                <x-home.collection-swiper-slide
                    title="{{$category->name}}"
                    url="{{route('product.category',$bundle)}}"
                    :media="$category->getFirstMedia(config('const.media.featured'))"
                    subtitle="{{'Check out our' . $category->name }}"
                    fit="object-contain"
                />
            @endif
        @endforeach
    </x-home.collection-row>
</x-home.section-container>
