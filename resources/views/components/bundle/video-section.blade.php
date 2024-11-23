@props([
    /**@var\App\Models\Bundle*/"bundle",
])

<x-home.section-container
    class="relative mt-10 flex h-[850px] items-center justify-center overflow-hidden"
>
    <div
        style="aspect-ratio: 1"
        class="absolute inset-0 h-full w-full object-cover"
    >
        {!! $bundle->url !!}
    </div>

    <div
        class="padding-from-side-menu absolute bottom-14 start-0 z-[10] w-[60%] text-lightColor"
    >
        <h2 class="headline-font">{{ __("store.Tutorial") }}</h2>
        <p class="mt-2 text-[13px] font-[200] uppercase tracking-[2px]">
            {{ __("store.Watch the full tutorial video to learn how to use!") }}
        </p>
    </div>
</x-home.section-container>
