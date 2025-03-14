@props([
    "icon",
    "title",
    "content",
    "width" => "w-fit lg:w-[80%]",
])

<div {{ $attributes }}>
    <div
        class="{{ $width }} mx-auto flex flex-col items-center gap-x-4 gap-y-4 lg:flex-row lg:items-start lg:gap-y-0"
    >
        <img
            class="h-5 w-5"
            src="{{ $icon }}"
            alt="{{ __("store.Headphone Icon") }}"
        />
        <div class="text-center lg:text-start">
            <h3 class="mb-2 text-[17px] font-medium leading-[20px]">
                {{ $title }}
            </h3>
            <p class="text-sm font-light">{{ $content }}</p>
        </div>
    </div>
</div>
