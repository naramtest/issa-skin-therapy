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

        menuItems: [
            { title: '{{ __("store.Home") }}', url: '#' },
            { title: '{{ __("store.Shop") }}', url: '#' },
            { title: '{{ __("store.About") }}', url: '#' },
            { title: '{{ __("store.Contact Us") }}', url: '#' },
        ],
        ...headerAnimations(),
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
