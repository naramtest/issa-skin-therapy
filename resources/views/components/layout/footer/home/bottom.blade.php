<div
    {{ $attributes->class(["content-x-padding grid content-center items-center justify-between gap-1 pb-5 pt-1 text-sm font-light text-lightColor lg:grid-flow-col"]) }}
>
    <div class="text-center lg:text-start">
        © {{ now()->year }} {{ $info->name }}.
    </div>
    <div
        class="flex flex-col items-center justify-center lg:flex-row lg:justify-end lg:gap-x-4"
    >
        <x-shared.local-switcher location="bottom" />
        <livewire:currency-selector location="bottom" />
    </div>
    <img
        class="block lg:w-[300px]"
        src="{{ asset("storage/images/payment-methods.webp") }}"
        alt="{{ __("store.Payment methods") }}"
    />
</div>
