@props([
    "post",
])
<article class="flex h-[48%] items-start justify-between">
    <div class="relative h-full w-[40%]">
        {!! \App\Helpers\Media\ImageGetter::responsiveFeaturedImg($post, class: "h-full  object-cover rounded-2xl w-full") !!}
        {{-- TODO: add url to archive page --}}
        <div
            class="absolute start-4 top-4 w-fit rounded-3xl bg-secondaryColor px-6 py-2 text-xs"
        >
            <p class="text-darkColor">
                {{ $post->categories->first()->name }}
            </p>
        </div>
    </div>
    <div class="w-[57%] py-4 text-darkColor">
        <x-posts.card-content :post="$post" />
    </div>
</article>
