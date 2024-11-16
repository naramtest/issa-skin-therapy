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
        class="dark-35-tr-tl dark-35-tl-tr absolute right-0 top-full z-[1000] flex w-[max-content] flex-col gap-y-[1px] rounded-b-lg bg-darkColor p-3 text-lightColor shadow [&[x-cloak]]:hidden"
        x-transition:enter="transition duration-200 ease-out"
        x-transition:enter-start="-translate-y-1 opacity-0"
        x-transition:enter-end="translate-y-0 opacity-100"
        x-transition:leave="transition duration-150 ease-in"
        x-transition:leave-start="translate-y-0 opacity-100"
        x-transition:leave-end="-translate-y-1 opacity-0"
        x-show="open"
        x-cloak
    >
        {{ $dropdown }}
    </ul>
</div>
