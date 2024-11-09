<div
    id="nav"
    class="relative bg-darkColor"
    x-data="{
        searchOpen: false,
        sticky: false,
        scrollPosition: 0,
        navOffset: 0,
        open: false,
        mobileMenu: false,
        animated: false,
        animateItems() {
            gsap.to(this.$refs.divider, {
                width: '100%',
                duration: 0.9,
                ease: 'power1.in',
                delay: 0.5,
            })
            gsap.fromTo(
                '.menu-item',
                {
                    opacity: 0,
                    x: 50,
                },
                {
                    opacity: 1,
                    x: 0,
                    duration: 0.3,
                    stagger: 0.1,
                    ease: 'power2.out',
                    onComplete: () => {
                        this.animated = true // Mark as animated when complete
                    },
                },
            )
            // Animate card content
            gsap.fromTo(
                '.card-content',
                {
                    x: 30,
                    opacity: 0,
                },
                {
                    x: 0,
                    opacity: 1,
                    duration: 0.6,
                    delay: 0.3,
                    ease: 'power2.in',
                },
            )
        },
        resetAnimations() {
            this.animated = false // Reset animation state
            gsap.set('.menu-item', {
                opacity: 0,
                x: 20,
            })
            gsap.set(this.$refs.divider, {
                width: 0,
            })
            gsap.set('.card-content', {
                x: 30,
                opacity: 0,
            })
        },
        menuItems: [
            { title: '{{ __("store.Home") }}', url: '#' },
            { title: '{{ __("store.Shop") }}', url: '#' },
            { title: '{{ __("store.About") }}', url: '#' },
            { title: '{{ __("store.Contact Us") }}', url: '#' },
        ],
        ...headerAnimations().setupMobileMenuAnimations(),
    }"
    @mouseleave="open = false; resetAnimations()"
    x-init="
        navOffset = $el.offsetTop
        window.addEventListener('scroll', () => {
            scrollPosition = window.scrollY
            sticky = scrollPosition > navOffset
        })
        $watch('mobileMenu', (value) => {
            if (value) {
                openMenu()
            } else {
                closeMenu()
            }
        })
    "
    :class="{
            'fixed top-0 left-0 w-full z-50 shadow-md': sticky,
            'relative': !sticky
        }"
>
    {{ $slot }}
</div>
