<div {{ $attributes->class(["h-[550px] w-full bg-lightColor lg:h-[100vh]"]) }}>
    <div
        style="
            background-image: url({{ asset("storage/images/about-us-hero.webp") }});
            background-position: center center;
            background-repeat: no-repeat;
            background-size: cover;
        "
        class="card-overlay card-opacity-10 relative h-full w-full rounded-t-[1.25rem]"
    >
        <div
            class="padding-from-side-menu absolute bottom-[20%] start-0 z-[10] text-lightColor lg:bottom-[13%]"
        >
            <p class="text-[17px]">{{ __("store.Our Story") }}</p>
            <h1 class="mt-2 text-4xl font-bold lg:mt-6 lg:text-[75px]">
                {{ __("store.Skin That Defies Time") }}
            </h1>
        </div>
    </div>
</div>
