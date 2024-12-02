<div {{ $attributes->class(["h-[100vh] w-full bg-lightColor"]) }}>
    <div
        style="
            background-image: url({{ asset("storage/images/shop-bg.webp") }});
            background-position: top center;
            background-repeat: no-repeat;
            background-size: cover;
        "
        class="relative h-full w-full rounded-t-[1.25rem]"
    >
        <div
            class="padding-from-side-menu absolute bottom-[15%] start-0 text-lightColor"
        >
            <div class="flex">
                <a
                    class="hover:text-lightAccentColor"
                    href="{{ route("bundles.index") }}"
                >
                    <span>{{ __("dashboard.Collections") }}</span>
                </a>
                <a
                    class="ms-3 hover:text-lightAccentColor"
                    href="{{ route("shop.index") }}"
                >
                    <span>{{ __("store.All Products") }}</span>
                </a>
            </div>
            <h1 class="text-[75px] font-bold">
                {{ __("dashboard.Our Products") }}
            </h1>
        </div>
    </div>
</div>
