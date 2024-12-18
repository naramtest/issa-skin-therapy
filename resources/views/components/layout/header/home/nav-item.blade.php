@props([
    "title",
    "url",
])

<li
    @mouseenter="open = false; $nextTick(() => animateItems())"
    {{ $attributes->class(["nav-item group relative flex cursor-pointer items-center overflow-hidden px-4 py-2 font-medium"]) }}
>
    <a href="{{ $url }}">
        <span class="nav-item-transition-opacity relative h-full w-full">
            {{ $title }}
        </span>
        <span
            class="nav-item-transition absolute left-0 top-0 h-full w-full translate-y-full scale-0 rounded-[3.125rem] bg-darkColor px-4 py-2 text-center text-lightColor group-hover:translate-y-0 group-hover:scale-100 group-hover:text-lightColor"
        >
            {{ $title }}
        </span>
    </a>
</li>
