@props([
    "beforeImage",
    "afterImage",
    "beforeAlt" => "Before image",
    "afterAlt" => "After image",
    "overlayText" => null,
])

<div
    {{ $attributes->class(["image-comparison"]) }}
>
    <img
        src="{{ $beforeImage }}"
        alt="{{ $beforeAlt }}"
        class="before-image"
    />
    @if ($overlayText)
        <div
            class="text-overlay px-[5rem] text-white transition-opacity duration-300"
        >
            <p>{{ __("store.Check out A-Luminate One Collection") }}</p>
            <h2 class="text-2xl font-bold">
                {{ __("store.After 3 months") }}
            </h2>
        </div>
    @endif

    <img src="{{ $afterImage }}" alt="{{ $afterAlt }}" class="after-image" />
    <div class="comparison-slider flex items-center">
        <span class="">
            <svg
                class="text-black"
                viewBox="0 0 12 17"
                stroke="currentColor"
                fill="none"
                xmlns="http://www.w3.org/2000/svg"
            >
                <path
                    stroke-linecap="round"
                    stroke-width="1.5"
                    d="M1 1L0.999999 16"
                ></path>
                <path
                    stroke-linecap="round"
                    stroke-width="1.5"
                    d="M6 1L6 16"
                ></path>
                <path
                    stroke-linecap="round"
                    stroke-width="1.5"
                    d="M11 1L11 16"
                ></path>
            </svg>
        </span>
    </div>
</div>
