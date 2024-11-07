<span
    class="absolute left-0 h-0.5 w-full bg-black transition-all duration-300"
    :class="{ 'top-3 rotate-45': mobileMenu, 'top-1': !mobileMenu }"
></span>
<span
    class="absolute left-0 top-3 h-0.5 w-full bg-black transition-opacity duration-300"
    :class="{ 'opacity-0': mobileMenu }"
></span>
<span
    class="absolute left-0 h-0.5 w-full bg-black transition-all duration-300"
    :class="{ 'top-3 -rotate-45': mobileMenu, 'top-5': !mobileMenu }"
></span>
