@props([
    /**@var\App\Models\Bundle*/"bundle",
])

<x-home.section-container
    class="padding-from-side-menu flex flex-col justify-between gap-x-12 gap-y-12 bg-darkColor py-12 md:flex-row lg:gap-x-24"
>
    <div class="md:w-1/2">
        <img
            class="h-[350px] w-full rounded-xl object-cover lg:h-[500px]"
            src="{{ asset("storage/images/ice.webp") }}"
            alt="{{ __("store.decoration") }}"
        />
    </div>
    <div class="flex flex-col items-start justify-center md:w-1/2">
        <h2 class="headline-font text-lightColor">
            {{ __("dashboard.Extra Tips") }}
        </h2>
        <div class="no-tailwind extra text-lightColor md:mt-4">
            {!! $bundle->extra_tips !!}
        </div>
    </div>
</x-home.section-container>
