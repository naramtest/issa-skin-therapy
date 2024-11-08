@props([
    "title",
])

<li
    x-data="{
        open: false,
        animated: false, // Add this to track animation state
        animateItems() {
            if (! this.animated) {
                gsap.to(this.$refs.divider, {
                    width: '100%',
                    duration: 0.9,
                    ease: 'power2.out',
                    delay: 0.5, // Adjust this to control when the line starts animating
                })
                gsap.fromTo(
                    '.menu-item',
                    {
                        opacity: 0,
                        x: 20,
                    },
                    {
                        opacity: 1,
                        x: 0,
                        duration: 0.5,
                        stagger: 0.1,
                        ease: 'power2.out',
                        onComplete: () => {
                            this.animated = true // Mark as animated when complete
                        },
                    },
                )
            }
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
        },
    }"
    @mouseleave="open = false; resetAnimations()"
    {{ $attributes->class(["nav-padding "]) }}
>
    <div
        @mouseenter="open = true; $nextTick(() => animateItems())"
        class="cursor-pointer rounded-[3.125rem] font-medium"
    >
        <div
            class="nav-item group relative flex cursor-pointer items-center overflow-hidden px-4 py-2 font-medium"
        >
            <span class="nav-item-transition-opacity relative h-full w-full">
                {{ $title }}
            </span>
            <span
                class="nav-item-transition absolute left-0 top-0 h-full w-full translate-y-full scale-0 rounded-[3.125rem] bg-darkColor px-4 py-2 text-center text-lightColor group-hover:translate-y-0 group-hover:scale-100 group-hover:text-lightColor"
            >
                {{ $title }}
            </span>
        </div>

        {{-- Mega Menu --}}
        <div
            class="absolute left-0 top-[100%] z-[10] flex w-full gap-12 bg-lightColor bg-white px-24 py-10 pe-10 text-[#1f1f1f] shadow-lg"
            x-transition:enter="transform transition duration-300 ease-out"
            x-transition:enter-start="-translate-y-full opacity-0"
            x-transition:enter-end=" translate-y-0 opacity-100"
            x-transition:leave="transition duration-300 ease-out"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            x-show="open"
        >
            <div class="w-1/2">
                <div
                    class="grid auto-rows-fr grid-cols-2 gap-3 font-normal text-[#1f1f1f]"
                >
                    <!-- Add menu-item class to each category -->
                    <div class="menu-item translate-x-2 opacity-0">
                        <h2 class="text-base font-bold">Cleanse</h2>
                        <ul class="mt-3">
                            <li>Lumi cleanse Cleasner</li>
                            <li class="mt-1">SaliCleanse Cleasner</li>
                        </ul>
                    </div>

                    <div class="menu-item translate-x-2 opacity-0">
                        <h2 class="text-base font-bold">Hydrate</h2>
                        <ul class="mt-3">
                            <li>LumiHydra Anti-Oxidant Emulsion</li>
                            <li class="mt-1">PureHydra Oil-Free lotion</li>
                        </ul>
                    </div>

                    <div class="menu-item translate-x-2 opacity-0">
                        <h2 class="text-base font-bold">Treat</h2>
                        <ul class="mt-3 font-normal">
                            <li>A-Clear</li>
                            <li class="mt-1">A-Luminate</li>
                            <li class="mt-1">X-Age</li>
                        </ul>
                    </div>

                    <div class="menu-item translate-x-2 opacity-0">
                        <h2 class="text-base font-bold">Protect</h2>
                        <ul class="mt-3">
                            <li>LumiGaurd Broad Spectrum emulsion</li>
                        </ul>
                    </div>
                </div>

                <div class="menu-item translate-x-2 opacity-0">
                    <h2 class="mt-4 text-base font-bold">Collections</h2>
                    <ul class="mt-2 grid auto-rows-fr grid-cols-2 font-normal">
                        <li>X-Age Collection</li>
                        <li class="mt-1">A-Luminate One</li>
                        <li class="mt-1">A-Luminate Two</li>
                        <li class="mt-1">A-Clear Collection</li>
                    </ul>
                </div>

                <div
                    x-ref="divider"
                    class="my-6 h-[1px] bg-gray-300"
                    style="width: 0"
                ></div>

                <div
                    class="menu-item flex translate-x-2 items-center justify-between opacity-0"
                >
                    <h2 class="text-xl font-bold">Visit shop</h2>
                    <x-icons.arrow-right class="h-5 w-5" />
                </div>
            </div>

            <div class="w-1/2">
                <div class="grid auto-rows-fr grid-cols-2 gap-4">
                    <!-- Add menu-item class to cards -->
                    <div
                        class="menu-item card-one card-background card-overlay full-rounded relative min-h-[412px] w-full translate-x-2 rounded-2xl px-6 opacity-0"
                    >
                        <div
                            class="absolute inset-x-6 bottom-6 z-10 text-white"
                        >
                            <div class="flex items-center justify-between">
                                <h3
                                    class="text-underline text-underline-black text-xl font-bold"
                                >
                                    {{ __("store.Shop by collection") }}
                                </h3>
                                <x-icons.card-arrow-right
                                    class="arrow h-5 w-5"
                                />
                            </div>
                            <p class="mt-2 font-normal">
                                {{ __("store.Check out all") }}
                            </p>
                        </div>
                    </div>
                    <div
                        class="menu-item card-tow card-background card-overlay full-rounded relative min-h-[412px] w-full translate-x-2 rounded-2xl opacity-0"
                    >
                        <div
                            class="absolute inset-x-6 bottom-6 z-10 text-white"
                        >
                            <div class="flex items-center justify-between">
                                <h3
                                    class="text-underline text-underline-black text-xl font-bold"
                                >
                                    {{ __("store.All Products") }}
                                </h3>
                                <x-icons.card-arrow-right
                                    class="arrow h-5 w-5"
                                />
                            </div>
                            <p class="mt-2 font-normal">
                                {{ __("store.Check out  all our products") }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</li>

<style>
    /* Your existing styles */

    .menu-item {
        transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .menu-item.show {
        opacity: 1;
        transform: translateX(0);
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateX(10px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    .card-background {
        background-position: center center;
        background-repeat: no-repeat;
        background-size: cover;
    }

    .card-one {
        background-image: url({{ asset("storage/images/all-collection-3.webp") }});
    }

    .card-tow {
        background-image: url({{ asset("storage/images/4.webp") }});
    }
</style>
