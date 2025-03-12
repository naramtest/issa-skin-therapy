@props([
    /**@var\mixed*/"post",
])

<article
    {{ $attributes->class(["relative h-[36rem] lg:h-[42rem]"]) }}
>
    <a
        class="card-opacity-10 card-overlay relative h-full"
        href="{{ route("posts.show", $post) }}"
        aria-label="{{ $post->title }}"
    >
        {!! \App\Helpers\Media\ImageGetter::responsiveFeaturedImg($post, class: "h-full w-full object-cover rounded-2xl") !!}
    </a>
    <div class="absolute bottom-10 z-[30] px-8 text-white">
        @if (count($post->categories))
            <x-blog.category-label :post="$post" class="bg-[#92E1D8]" />

            <div class="mt-4 flex divide-x-2 divide-white rtl:divide-x-reverse">
                <div class="flex items-center pe-3">
                    <img
                        class="h-4 w-4"
                        src="{{ asset("storage/icons/calendar.svg") }}"
                        alt="{{ __("dashboard.Calender Icon") }}"
                    />
                    <p class="ms-1 text-xs">
                        {{ formattedDate($post->published_at) }}
                    </p>
                </div>
                {{-- TODO: edit when Add Comments --}}
                <div class="flex items-center ps-3">
                    <img
                        class="h-4 w-4"
                        src="{{ asset("storage/icons/comments.svg") }}"
                        alt="{{ __("store.Comments Icon") }}"
                    />
                    <p class="ms-1 text-xs">
                        {{ __("store.No Comments") }}
                    </p>
                </div>
            </div>
            <h2 class="py-5 text-4xl font-bold leading-[43px]">
                <a href="{{ route("posts.show", $post) }}">
                    {{ $post->title }}
                </a>
            </h2>

            <p class="line-clamp-2 text-lg">
                {{ $post->excerpt }}
            </p>

            <a
                class="mt-5 inline-block text-sm underline transition-colors duration-300 hover:text-secondaryColor"
                href="{{ route("posts.show", $post) }}"
                aria-label="{{ "Continue reading: " . $post->title }}"
            >
                {{ __("store.Read Full Article") }}
            </a>
        @endif
    </div>
</article>
