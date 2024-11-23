@props([
    /**@var\App\Models\Bundle*/"bundle",
])

<x-home.section-container
    class="padding-from-side-menu flex justify-between gap-24 bg-darkColor py-12"
>
    <div class="w-1/2">
        <img
            class="h-[500px] w-full rounded-xl object-cover"
            src="https://issaskintherapy.com/wp-content/uploads/2024/07/06-576x1024.webp"
            alt=""
        />
    </div>
    <div class="flex w-1/2 flex-col items-start justify-center">
        <h2 class="headline-font text-lightColor">
            {{ __("dashboard.Extra Tips") }}
        </h2>
        <div class="no-tailwind mt-4 text-lightColor">
            {!! $bundle->extra_tips !!}
        </div>
    </div>
</x-home.section-container>
