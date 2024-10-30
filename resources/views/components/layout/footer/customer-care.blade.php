@props([
    "icon",
    "title",
    "content",
    "width" => "w-[80%]",
])

<div {{ $attributes }}>
    <div class="{{ $width }} mx-auto flex items-start gap-x-4">
        <img
            class="h-5 w-5"
            src="{{ $icon }}"
            alt="{{ __("store.Headphone Icon") }}"
        />
        <div class="">
            <h3 class="mb-2 text-[17px] font-medium leading-[20px]">
                {{ $title }}
            </h3>
            <p class="text-sm font-light">{{ $content }}</p>
        </div>
    </div>
</div>
