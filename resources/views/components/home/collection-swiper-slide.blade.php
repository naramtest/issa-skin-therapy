@props([
    "title",
    "url",
    "media",
    "subtitle",
    "fit" => "object-cover",
])

<article
    {{ $attributes->class(["swiper-slide card-hover-trigger"]) }}
>
    <a
        class="flex h-[300px] w-full flex-col rounded-[15px] bg-[#FAFAFA] md:h-[450px]"
        href="{{ $url }}"
    >
        <div class="rounded-inherit h-full flex-1 overflow-hidden bg-white">
            {!! \App\Helpers\Media\ImageGetter::responsiveImgElement($media, class: "hover:scale-110 transition-transform duration-300 rounded-inherit w-full h-full $fit") !!}
        </div>
        <div class="px-7 py-3 md:py-5">
            <div class="flex items-center justify-between">
                <h3
                    class="text-underline text-underline-black text-xl font-bold"
                >
                    {{ $title }}
                </h3>
                <x-icons.card-arrow-right class="arrow h-5 w-5" />
            </div>
            <p class="mt-2">{{ $subtitle }}</p>
        </div>
    </a>
</article>
