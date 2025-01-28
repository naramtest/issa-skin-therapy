@props([
    "bundle",
])

<a
    href="{{ route("product.bundle", ["bundle" => $bundle->slug]) }}"
    {{ $attributes->class(["card-hover-trigger block !flex flex-col rounded-[15px] bg-[#FAFAFA]"]) }}
>
    <div class="rounded-inherit h-[200px] w-full overflow-hidden lg:h-[360px]">
        {!! \App\Helpers\Media\ImageGetter::responsiveFeaturedImg($bundle, class: "rounded-inherit h-[360px] w-full object-cover hover:scale-105  transition-transform duration-300") !!}
    </div>
    <div class="px-4 py-5 lg:px-7">
        <div class="flex items-center justify-between">
            <h3 class="text-underline text-underline-black text-xl font-bold">
                {{ preg_replace("/\bcollection\b/i", "", $bundle->name) }}
            </h3>
            <x-icons.card-arrow-right class="arrow h-5 w-5 rtl:rotate-180" />
        </div>
        <p class="mt-2">{{ $bundle->subtitle }}</p>
    </div>
</a>
