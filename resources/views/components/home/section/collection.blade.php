@props(['bundles' , 'categories'])
<x-home.section-container class="content-x-padding mt-10 py-14">
    <div class="flex items-center justify-between px-14">
        <div class="w-[25%]">
            <x-home.fancy-heading />
            <a

                class="mt-10 flex w-fit items-center rounded-[50px] border border-[#a5bbc4] px-6 py-4 text-[15px] font-medium transition-colors duration-300 hover:border-transparent hover:bg-lightAccentColor"
                href="{{route('about.index')}}"
            >
                <span>{{ __("store.Our Story") }}</span>
                <x-icons.arrow-right class="ms-3 h-4 w-4 text-black" />
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
    <x-home.collection-row title="{{__('store.Shop by collection')}}" subtitle="{{__('store.Check out all')}}"
                           image="{{asset('storage/images/bundle-home-collection.webp')}}"
                           url="{{route('bundles.index')}}">
        @foreach($bundles as $bundle)
            <x-home.collection-swiper-slide title="{{$bundle->name}}" url="{{route('product.bundle',$bundle)}}"
                                            :media="$bundle->getFirstMedia(config('const.media.featured'))"
                                            subtitle="{!! $bundle->subtitle !!}" />

        @endforeach
    </x-home.collection-row>

    <x-home.collection-row title="{{__('store.Shop by collection')}}" subtitle="{{__('store.Check out all')}}"
                           image="{{asset('storage/images/bundle-home-collection.webp')}}"
                           url="{{route('bundles.index')}}">
        @foreach($categories as $category)
            @if(Str::contains($category->name , '&') )
            @else
                <x-home.collection-swiper-slide title="{{$bundle->name}}" url="{{route('product.bundle',$bundle)}}"
                                                :media="$bundle->getFirstMedia(config('const.media.featured'))"
                                                subtitle="{!! $bundle->subtitle !!}" />
            @endif

        @endforeach
    </x-home.collection-row>

</x-home.section-container>
