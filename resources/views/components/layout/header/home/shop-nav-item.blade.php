@props([
    "title",
])

{{-- TODO: add animation for the color --}}

<li @mouseleave="open = false" {{ $attributes->class(["nav-padding"]) }}>
    <div
        @mouseenter="open = true"
        class="cursor-pointer rounded-[3.125rem] px-4 py-2 font-medium transition-all duration-300 hover:bg-darkColor hover:text-lightColor"
    >
        <p class="">{{ $title }}</p>
        <div
            class="absolute left-0 top-[100%] z-[10] w-full shadow"
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
