@props([
    /**@var\mixed*/"post",
])

<div class="flex items-center pe-3">
    <x-icons.blod-date class="h-5 w-5 text-darkColor" />

    {{-- TODO: add archived page --}}
    <p class="ms-1 text-xs">
        {{ formattedDate($post->published_at) }}
    </p>
</div>

<h2 class="py-5 text-2xl font-bold leading-[30px]">
    <a
        href="{{ route("posts.show", $post) }}"
        aria-label="Read full article: {{ $post->title }}"
    >
        {{ $post->title }}
    </a>
</h2>

<p class="my-2 line-clamp-3 text-base leading-[25px] text-[#333F43]">
    {{ $post->excerpt }}
</p>

<a
    class="mt-5 inline-block text-sm underline transition-colors duration-300 hover:text-[#5d5d5d]"
    href="{{ route("posts.show", $post) }}"
    aria-label="Continue reading full article: {{ $post->title }}"
>
    {{ __("store.Read Full Article") }}
</a>
