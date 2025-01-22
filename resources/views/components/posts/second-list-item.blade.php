@props([
    "post",
])
<article
    {{ $attributes->class(["flex flex-col items-start justify-between md:h-[47%] md:flex-row"]) }}
>
    <div class="relative h-full w-full md:w-[40%]">
        {!! \App\Helpers\Media\ImageGetter::responsiveFeaturedImg($post, class: "lg:h-full md:h-[300px] h-[200px] object-cover rounded-2xl w-full") !!}
        <x-blog.category-label
            :post="$post"
            class="absolute start-4 top-4 bg-secondaryColor"
        />
    </div>
    <div class="py-4 text-darkColor md:w-[57%]">
        <x-posts.card-content :post="$post" />
    </div>
</article>
