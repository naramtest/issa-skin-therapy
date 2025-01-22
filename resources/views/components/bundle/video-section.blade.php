@props([
    /**@var\App\Models\Bundle*/"bundle",
])

<x-home.section-container
    class="relative mt-10 flex h-[450px] items-center justify-center overflow-hidden lg:h-[850px]"
>
    <div
        style="aspect-ratio: 1"
        class="absolute inset-0 h-full w-full object-cover"
    >
        {!! $bundle->url !!}
    </div>

    <div
        class="padding-from-side-menu absolute bottom-14 start-0 z-[10] text-lightColor lg:w-[60%]"
    >
        <h2 class="headline-font">{{ __("store.Tutorial") }}</h2>
        <p class="mt-2 text-[13px] uppercase lg:font-[200] lg:tracking-[2px]">
            {{ __("store.Watch the full tutorial video to learn how to use!") }}
        </p>
    </div>
</x-home.section-container>
