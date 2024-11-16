<div
    {{ $attributes }}
    x-data="{
        open: false,
        animateItems() {
            gsap.fromTo(
                $refs.dropdownList.children,
                { x: 20, opacity: 0 },
                {
                    x: 0,
                    opacity: 1,
                    duration: 0.4,
                    stagger: 0.03,
                    ease: 'power2.out',
                    clearProps: 'all',
                },
            )
        },
    }"
    class="relative py-2"
    @mouseenter="open = true; $nextTick(() => animateItems())"
    @mouseleave="open = false"
>
    <button
        @click="open = !open; if(open) $nextTick(() => animateItems())"
        class="flex items-center gap-x-1 rounded-lg px-4 py-2 text-sm text-white"
    >
        {{ $button }}
    </button>

    <ul
        x-ref="dropdownList"
        class="absolute right-0 top-0 z-[1000] flex w-[max-content] -translate-y-[98%] flex-col gap-y-[1px] rounded-t-lg bg-darkColor p-3 text-lightColor shadow [&[x-cloak]]:hidden"
        x-transition:enter="transition duration-200 ease-out"
        x-transition:enter-start="translate-y-0 opacity-0"
        x-transition:enter-end="-translate-y-[98%] opacity-100"
        x-transition:leave="transition duration-150 ease-in"
        x-transition:leave-start="-translate-y-[98%] opacity-100"
        x-transition:leave-end="translate-y-0 opacity-0"
        x-show="open"
        x-cloak
    >
        {{ $dropdown }}
    </ul>
</div>
