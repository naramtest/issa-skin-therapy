@props([
    "label",
])
<div {{ $attributes->class(["h-[450px] w-full bg-lightColor lg:h-[90vh]"]) }}>
    <div
        {{-- TODO: edit image --}}
        style="
            background-image: url({{ asset("storage/images/shop-bg.webp") }});
            background-position: top center;
            background-repeat: no-repeat;
            background-size: cover;
        "
        class="relative h-full w-full rounded-t-[1.25rem]"
    >
        <div
            class="padding-from-side-menu absolute bottom-[5%] start-0 text-lightColor lg:bottom-[15%]"
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
            <h1 class="mt-4 text-[2.25rem] font-bold lg:text-[75px]">
                {{ $label }}
            </h1>
        </div>
    </div>
</div>
