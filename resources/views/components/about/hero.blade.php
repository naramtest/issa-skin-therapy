<div {{ $attributes->class(['h-[100vh] w-full bg-lightColor']) }}>
    <div
        style="
            background-image: url({{ asset("storage/images/about-us-hero.webp") }});
                background-position: center center;
    background-repeat: no-repeat;
    background-size: cover;
        "
        class="relative h-full w-full rounded-t-[1.25rem]"
    >
        <div
            class="padding-from-side-menu absolute bottom-[9%] start-0 text-lightColor"
        >
            <p class="text-[17px]">{{ __('store.Our Story') }}</p>
            <h1 class="text-[75px] font-bold">{{ __('store.Skin That Defies Time') }}</h1>
        </div>
    </div>
</div>
