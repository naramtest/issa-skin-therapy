@props([
    "post",
])
<article class="flex h-[48%] items-start justify-between">
    <div class="relative h-full w-[40%]">
        {!! \App\Helpers\Media\ImageGetter::responsiveFeaturedImg($post, class: "h-full  object-cover rounded-2xl w-full") !!}
        <x-blog.category-label
            :post="$post"
            class="absolute start-4 top-4 bg-secondaryColor"
        />
    </div>
    <div class="w-[57%] py-4 text-darkColor">
        <x-posts.card-content :post="$post" />
    </div>
</article>
