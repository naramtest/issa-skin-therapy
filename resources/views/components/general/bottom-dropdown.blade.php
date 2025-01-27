<div
    {{ $attributes }}
    x-data="{
        open: false,
        isMobile: false,
        init() {
            this.isMobile = window.innerWidth < 1024
            window.addEventListener('resize', () => {
                this.isMobile = window.innerWidth < 1024
            })
        },
        isInsideComponent(event) {
            if (this.isMobile) return false
            const dropdown = $refs.dropdownList
            const button = $refs.button
            return button.contains(event.relatedTarget)
        },
        animateItems() {
            gsap.fromTo(
                $refs.dropdownList.children,
                { x: 20, opacity: 0 },
                {
                    x: 0,
                    opacity: 1,
                    duration: 0.6,
                    stagger: 0.03,
                    ease: 'power2.out',
                    clearProps: 'all',
                },
            )
        },
        handleMouseEnter() {
            if (! this.isMobile) {
                this.open = true
                this.$nextTick(() => this.animateItems())
            }
        },
        handleMouseLeave(event) {
            if (! this.isMobile && ! this.isInsideComponent(event)) {
                this.open = false
            }
        },
        handleClickAway(event) {
            // Check if the clicked element is outside both the button and dropdown
            if (
                ! $refs.button.contains(event.target) &&
                ! $refs.dropdownList.contains(event.target)
            ) {
                this.open = false
            }
        },
    }"
    class="relative py-2"
    @mouseenter="handleMouseEnter()"
    @mouseleave="handleMouseLeave($event)"
    @click.away="handleClickAway($event)"
>
    <button
        x-ref="button"
        @click="open = !open; if(open) $nextTick(() => animateItems())"
        class="flex items-center gap-x-1 rounded-lg px-4 py-2 text-sm text-white"
    >
        {{ $button }}
    </button>

    <ul
        x-ref="dropdownList"
        x-bind:class="open ? 'pointer-events-auto' : 'pointer-events-none'"
        class="absolute right-0 top-0 z-[1000] flex w-[max-content] -translate-y-[98%] flex-col gap-y-[1px] rounded-t-lg bg-darkColor p-3 text-lightColor shadow [&[x-cloak]]:hidden"
        x-transition:enter="transition duration-[.5s] ease-out"
        x-transition:enter-start="translate-y-0 opacity-0"
        x-transition:enter-end="-translate-y-[98%] opacity-100"
        x-transition:leave="transition duration-[.45s] ease-in"
        x-transition:leave-start="-translate-y-[98%] opacity-100"
        x-transition:leave-end="translate-y-0 opacity-0"
        x-show="open"
        x-cloak
    >
        {{ $dropdown }}
    </ul>
</div>
