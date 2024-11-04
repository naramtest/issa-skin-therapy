@props([
    "title",
])

{{-- TODO: add animation for the color --}}

<li @mouseleave="open = false" {{ $attributes->class(["nav-padding"]) }}>
    <div
        @mouseenter="open = true"
        class="cursor-pointer rounded-[3.125rem] font-medium"
    >
        <div
            {{ $attributes->class(["nav-item group relative flex cursor-pointer items-center overflow-hidden px-4 py-2 font-medium"]) }}
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
        {{-- TODO: add shop nav --}}
        <div
            class="absolute left-0 top-[100%] z-[10] w-full bg-black"
            x-show="open"
            x-transition:enter="transform transition duration-300 ease-out"
            x-transition:enter-start="-translate-y-2 opacity-0"
            x-transition:enter-end="translate-y-0 opacity-100"
            x-transition:leave="transition duration-300 ease-out"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            x-cloak
        ></div>
    </div>
</li>
