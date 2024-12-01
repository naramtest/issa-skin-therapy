@props([
    'bundle'
])
<a href="{{route('product.bundle' , $bundle)}}" {{ $attributes->class(["swiper-slide"]) }}>
    {!! \App\Helpers\Media\ImageGetter::responsiveFeaturedImg($bundle , class: 'h-full w-full rounded-[15px] object-cover') !!}
</a>
