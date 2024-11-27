@props([
    "post",
])
<article
    {{ $attributes->class(["flex flex-col items-start justify-between"]) }}
>
    <div class="relative w-full">
        {!! \App\Helpers\Media\ImageGetter::responsiveFeaturedImg($post, class: "object-cover rounded-2xl w-full h-[310px]") !!}
        <x-blog.category-label
            :post="$post"
            class="absolute start-4 top-4 bg-secondaryColor"
        />
    </div>
    <div class="px-2 pt-8 text-darkColor">
        <x-posts.card-content :post="$post" />
    </div>
</article>
