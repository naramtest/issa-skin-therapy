@props([
    "post",
])
@if (count($post->categories))
    {{-- TODO: add url to archive page --}}
    <div
        {{ $attributes->class([" w-fit rounded-3xl bg-secondaryColor px-6 py-2 text-xs"]) }}
    >
        <p class="text-darkColor">
            {{ $post->categories->first()->name }}
        </p>
    </div>
@endif
