@props([
    "icon",
    "title",
])

<div
    {{ $attributes->class(["flex flex-col items-center justify-between gap-y-1"]) }}
>
    <img
        src="{{ asset("storage/icons/bottom/$icon") }}"
        alt="{{ $title }}"
        class="h-7 w-7"
    />
    <p class="text-xs">{{ $title }}</p>
</div>
