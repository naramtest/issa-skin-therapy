@props(['title' , 'url' , 'media' ,'subtitle'])

<article

    {{ $attributes->class(["swiper-slide card-hover-trigger"]) }}
>
    <a class="flex flex-col rounded-[15px] bg-[#FAFAFA] h-[450px] w-full" href="{{$url}}">
        <div class="flex-1 rounded-inherit overflow-hidden h-full">
            {!! \App\Helpers\Media\ImageGetter::responsiveImgElement($media , class: 'hover:scale-110 transition-transform duration-300 rounded-inherit w-full h-full object-cover') !!}
        </div>
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
