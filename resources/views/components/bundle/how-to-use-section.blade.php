@props([
    /**@var\App\Models\Bundle*/"bundle",
])

<x-home.section-container
    class="padding-from-side-menu relative z-10 -translate-y-10 bg-lightColor py-12"
>
    <h2 class="headline-font">{{ __("store.How to Use?") }}</h2>
    <div class="mt-12 grid grid-cols-1 gap-12 px-4 md:grid-cols-2 lg:gap-24">
        <div>
            <div class="flex items-center justify-center">
                <div
                    class="flex h-16 w-16 items-center justify-center rounded-full bg-darkColor"
                >
                    <img
                        src="{{ asset("storage/icons/sun.svg") }}"
                        alt="{{ __("store.icon") }}"
                    />
                </div>
                <p class="ms-4 text-lg font-medium">{{ __("store.AM") }}</p>
            </div>
            <div class="no-tailwind mt-8">
                {!! $bundle->how_to_use_am !!}
            </div>
        </div>
        <div>
            <div class="flex items-center justify-center">
                <div
                    class="flex h-16 w-16 items-center justify-center rounded-full bg-darkColor"
                >
                    <img
                        src="{{ asset("storage/icons/moon.svg") }}"
                        alt="{{ __("store.icon") }}"
                    />
                </div>
                <p class="ms-4 text-lg font-medium">{{ __("store.PM") }}</p>
            </div>
            <div class="no-tailwind mt-8">
                {!! $bundle->how_to_use_pm !!}
            </div>
        </div>
    </div>
</x-home.section-container>
