{{-- TODO: fix on mobile --}}

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
        let scrollY = 0

        window.addEventListener('scroll', () => {
            scrollPosition = window.scrollY
            sticky = scrollPosition > navOffset
        })

        $watch('mobileMenu', (value) => {
            if (value) {
                scrollY = window.scrollY
                document.body.style.overflow = 'hidden'
                document.body.style.position = 'fixed'
                document.body.style.top = `-${scrollY}px`
                document.body.style.width = '100%'
                openMenu()
            } else {
                closeMenu()
                document.body.style.position = ''
                document.body.style.overflow = ''
                document.body.style.top = ''
                document.body.style.width = ''
                window.scrollTo(0, scrollY)
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
