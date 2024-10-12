@props([
    "title",
])

{{-- TODO: add animation for the color --}}

<li
    class="cursor-pointer rounded-[3.125rem] px-4 py-2 font-medium transition-all duration-300 hover:bg-darkColor hover:text-lightColor"
>
    {{ $title }}
</li>
