@props(['title' , 'url' , 'media' ,'subtitle'])

<article

    {{ $attributes->class(["swiper-slide card-hover-trigger"]) }}
>
    <a class="flex flex-col rounded-[15px] bg-[#FAFAFA] h-full w-full" href="{{$url}}">
        {!! \App\Helpers\Media\ImageGetter::responsiveImgElement($media , class: 'rounded-inherit flex-1 object-cover') !!}
        <div class="px-7 py-5">
            <div class="flex items-center justify-between">
                <h3 class="text-underline text-underline-black text-xl font-bold">
                    {{$title}}
                </h3>
                <x-icons.card-arrow-right class="arrow h-5 w-5" />
            </div>
            <p class="mt-2">{{$subtitle}}</p>
        </div>
    </a>
</article>
