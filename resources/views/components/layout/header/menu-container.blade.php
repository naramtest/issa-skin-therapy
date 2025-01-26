<div
    id="nav"
    @toggle-mobile-menu.window="mobileMenu = !mobileMenu"
    class="relative bg-darkColor"
    x-data="{
        searchOpen: false,
        sticky: false,
        scrollPosition: 0,
        navOffset: 0,
        open: false,
        mobileMenu: false,

        ...headerAnimations(),
    }"
    @mouseleave="open = false; resetAnimations()"
    x-init="
        navOffset = $el.offsetTop
        window.addEventListener('scroll', () => {
            scrollPosition = window.scrollY
            sticky = scrollPosition > navOffset
        })
        // Listen for the custom event and update mobileMenu value

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
