<div
    {{ $attributes->class(["flex w-[50%] items-center justify-between gap-x-6"]) }}
>
    <img
        class="h-6 w-6"
        src="{{ asset("storage/icons/icon-left.svg") }}"
        alt="{{ __("store.arrow") }}"
    />
    <div class="flex items-center gap-x-4 text-white">
        <img
            src="{{ asset("storage/icons/truck.svg") }}"
            alt="{{ __("store.Truck Icon") }}"
        />
        <p>Free shipping in UAE over 270 AED and worldwide over $180</p>
    </div>
    <img
        class="h-6 w-6"
        src="{{ asset("storage/icons/icon-right.svg") }}"
        alt="{{ __("store.arrow") }}"
    />
</div>
