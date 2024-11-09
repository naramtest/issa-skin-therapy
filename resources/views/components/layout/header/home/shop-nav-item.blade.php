@props([
    "title",
])

<li
    {{ $attributes->class(["nav-padding "]) }}
>
    <div
        @mouseenter="open = true; $nextTick(() => animateItems())"
        class="cursor-pointer rounded-[3.125rem] font-medium"
    >
        <div
            class="nav-item group relative flex cursor-pointer items-center overflow-hidden px-4 py-2 font-medium"
        >
            <span class="nav-item-transition-opacity relative h-full w-full">
                {{ $title }}
            </span>
            <span
                class="nav-item-transition absolute left-0 top-0 h-full w-full translate-y-full scale-0 rounded-[3.125rem] bg-darkColor px-4 py-2 text-center text-lightColor group-hover:translate-y-0 group-hover:scale-100 group-hover:text-lightColor"
            >
                {{ $title }}
            </span>
        </div>
    </div>
</li>
