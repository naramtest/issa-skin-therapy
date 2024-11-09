<div
    class="absolute left-0 top-[100%] z-[10] flex w-full gap-12 overflow-hidden bg-lightColor bg-white px-24 py-10 pe-10 text-[#1f1f1f] shadow-lg"
    x-transition:enter="transform transition duration-300 ease-in"
    x-transition:enter-start="-translate-y-full "
    x-transition:enter-end=" translate-y-0 opacity-100"
    x-transition:leave="transition duration-200 ease-out"
    x-transition:leave-start="translate-y-0 opacity-100"
    x-transition:leave-end="-translate-y-full opacity-50"
    x-show="open"
>
    <div class="w-1/2">
        <div
            class="grid auto-rows-fr grid-cols-2 gap-3 font-normal text-[#1f1f1f]"
        >
            <!-- Cleanse Category -->
            <div class="menu-item translate-x-2 opacity-0">
                <h2 class="text-base font-bold">Cleanse</h2>
                <ul class="mt-3">
                    <li>
                        <a href="#" class="mega-menu-link group inline-block">
                            <span class="link-text">Lumi cleanse Cleanser</span>
                        </a>
                    </li>
                    <li class="mt-1">
                        <a href="#" class="mega-menu-link group inline-block">
                            <span class="link-text">SaliCleanse Cleanser</span>
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Hydrate Category -->
            <div class="menu-item translate-x-2 opacity-0">
                <h2 class="text-base font-bold">Hydrate</h2>
                <ul class="mt-3">
                    <li>
                        <a href="#" class="mega-menu-link group inline-block">
                            <span class="link-text">
                                LumiHydra Anti-Oxidant Emulsion
                            </span>
                        </a>
                    </li>
                    <li class="mt-1">
                        <a href="#" class="mega-menu-link group inline-block">
                            <span class="link-text">
                                PureHydra Oil-Free lotion
                            </span>
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Treat Category -->
            <div class="menu-item translate-x-2 opacity-0">
                <h2 class="text-base font-bold">Treat</h2>
                <ul class="mt-3">
                    <li>
                        <a href="#" class="mega-menu-link group inline-block">
                            <span class="link-text">A-Clear</span>
                        </a>
                    </li>
                    <li class="mt-1">
                        <a href="#" class="mega-menu-link group inline-block">
                            <span class="link-text">A-Luminate</span>
                        </a>
                    </li>
                    <li class="mt-1">
                        <a href="#" class="mega-menu-link group inline-block">
                            <span class="link-text">X-Age</span>
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Protect Category -->
            <div class="menu-item translate-x-2 opacity-0">
                <h2 class="text-base font-bold">Protect</h2>
                <ul class="mt-3">
                    <li>
                        <a href="#" class="mega-menu-link group inline-block">
                            <span class="link-text">
                                LumiGaurd Broad Spectrum emulsion
                            </span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Collections Section -->
        <div class="menu-item translate-x-2 opacity-0">
            <h2 class="mt-4 text-base font-bold">Collections</h2>
            <ul class="mt-2 grid auto-rows-fr grid-cols-2">
                <li>
                    <a href="#" class="mega-menu-link group inline-block">
                        <span class="link-text">X-Age Collection</span>
                    </a>
                </li>
                <li class="mt-1">
                    <a href="#" class="mega-menu-link group inline-block">
                        <span class="link-text">A-Luminate One</span>
                    </a>
                </li>
                <li class="mt-1">
                    <a href="#" class="mega-menu-link group inline-block">
                        <span class="link-text">A-Luminate Two</span>
                    </a>
                </li>
                <li class="mt-1">
                    <a href="#" class="mega-menu-link group inline-block">
                        <span class="link-text">A-Clear Collection</span>
                    </a>
                </li>
            </ul>
        </div>

        <div
            x-ref="divider"
            class="my-6 h-[2px] bg-gray-300"
            style="width: 0"
        ></div>

        <div
            class="menu-item flex translate-x-2 items-center justify-between opacity-0"
        >
            <a href="#" class="mega-menu-link group inline-block">
                <h2 class="link-text text-xl font-bold">Visit shop</h2>
            </a>
            <x-icons.arrow-right class="h-5 w-5" />
        </div>
    </div>

    <div class="w-1/2">
        <div class="grid auto-rows-fr grid-cols-2 gap-4">
            <!-- Collection Card -->
            <div
                class="menu-item card-one card-background card-overlay full-rounded relative min-h-[412px] w-full overflow-hidden rounded-2xl px-6 opacity-0"
            >
                <div
                    class="absolute inset-x-6 bottom-6 z-10 flex items-center justify-between gap-4 text-white"
                >
                    <div class="card-content">
                        <h3
                            class="text-underline text-underline-white transform text-xl font-bold"
                        >
                            {{ __("store.All Products") }}
                        </h3>
                        <p class="mt-2 transform font-normal">
                            {{ __("store.Shop by collection") }}
                        </p>
                    </div>
                    <x-icons.card-arrow-right class="arrow h-5 w-5" />
                </div>
            </div>

            <!-- All Products Card -->
            <div
                class="menu-item card-tow card-background card-overlay full-rounded relative min-h-[412px] w-full translate-x-4 overflow-hidden rounded-2xl opacity-0"
            >
                <div
                    class="absolute inset-x-6 bottom-6 z-10 flex items-center justify-between gap-4 text-white"
                >
                    <div class="card-content">
                        <h3
                            class="text-underline text-underline-white transform text-xl font-bold"
                        >
                            {{ __("store.All Products") }}
                        </h3>
                        <p class="mt-2 transform font-normal">
                            {{ __("store.Check out all our products") }}
                        </p>
                    </div>
                    <x-icons.card-arrow-right class="arrow h-5 w-5" />
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .menu-item {
        transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        transform: translateX(50%);
    }

    .mega-menu-link {
        position: relative;
        color: #1f1f1f;
        transition: color 0.3s ease;
    }

    .mega-menu-link .link-text {
        position: relative;
        display: inline-block;
    }

    .mega-menu-link .link-text::after {
        content: '';
        position: absolute;
        width: 100%;
        height: 1px;
        bottom: -2px;
        left: 0;
        background-color: currentColor;
        transform: scaleX(0);
        transform-origin: right;
        transition: transform 0.3s ease;
    }

    .mega-menu-link:hover .link-text::after {
        transform: scaleX(1);
        transform-origin: left;
    }

    .card-background {
        background-position: center center;
        background-repeat: no-repeat;
        background-size: cover;
        transition: transform 0.3s ease-out;
    }

    .card-background:hover {
        transform: scale(1.02);
    }

    .card-one {
        background-image: url({{ asset("storage/images/all-collection-3.webp") }});
    }

    .card-tow {
        background-image: url({{ asset("storage/images/4.webp") }});
    }

    .card-content {
        transform: translateX(50%);
        transition: transform 0.5s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .card-background:hover .card-content {
        transform: translateY(0);
    }

    .arrow {
        transition: transform 0.3s ease-out;
    }

    .card-background:hover .arrow {
        transform: rotate(90deg);
    }
</style>
