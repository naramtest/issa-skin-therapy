<div
    {{ $attributes->class(["content-x-padding grid grid-flow-col content-center items-center justify-between gap-1 pb-5 pt-1 text-sm font-light text-lightColor"]) }}
>
    <div>© {{ now()->year }} Issa Skin Therapy.</div>
    <div class="flex justify-end gap-x-4">
        <x-shared.local-switcher />
        {{-- TODO: make this dropdown opens to top not bottom --}}
        <x-shared.currency-switcher />
    </div>
    <img
        class="block w-[200px]"
        src="{{ asset("storage/images/payment-methods.webp") }}"
        alt="{{ __("store.Payment methods") }}"
    />
</div>
