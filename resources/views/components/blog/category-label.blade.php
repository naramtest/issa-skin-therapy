@props([
    "post",
])
{{-- THIs a test --}}
@if (count($post->categories))
    <a
        href="{{ route("posts.index", ["categoryId" => $post->categories->first()->id]) }}"
        {{ $attributes->class(["inline-block w-fit rounded-3xl bg-secondaryColor px-6 py-2 text-xs"]) }}
    >
        <p class="text-darkColor">
            {{ $post->categories->first()->name }}
        </p>
    </a>
@endif
