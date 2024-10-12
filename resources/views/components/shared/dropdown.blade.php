<div
    {{ $attributes }}
    x-data="{ open: false }"
    class="relative"
    @mouseenter="open = true"
    @mouseleave="open = false"
>
    <button
        @click="open = ! open"
        class="flex items-center gap-x-1 rounded-lg px-4 py-2 text-sm text-white"
    >
        {{ $button }}
    </button>

    <ul
        class="absolute left-1/2 top-full z-[10] min-w-[240px] origin-top-right -translate-x-1/2 rounded-lg border border-slate-200 bg-white p-2 shadow-xl [&[x-cloak]]:hidden"
        x-show="open"
        x-transition:enter="transform transition duration-200 ease-out"
        x-transition:enter-start="-translate-y-2 opacity-0"
        x-transition:enter-end="translate-y-0 opacity-100"
        x-transition:leave="transition duration-200 ease-out"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        x-cloak
    >
        {{ $dropdown }}
    </ul>
</div>
