@props([
    "post",
])
<article
    {{ $attributes->class(["flex flex-col items-start justify-between"]) }}
>
    <div class="relative w-full">
        {!! \App\Helpers\Media\ImageGetter::responsiveFeaturedImg($post, class: "object-cover rounded-2xl w-full h-[310px]") !!}
        {{-- TODO: add url to archive page --}}
        <div
            class="absolute start-4 top-4 w-fit rounded-3xl bg-secondaryColor px-6 py-2 text-xs"
        >
            <p class="text-darkColor">
                {{ $post->categories->first()->name }}
            </p>
        </div>
    </div>
    <div class="px-2 pt-8 text-darkColor">
        <x-posts.card-content :post="$post" />
    </div>
</article>
