<div
    {{ $attributes->class(["content-x-padding grid grid-flow-col content-center items-center justify-between gap-1 pb-5 pt-1 text-sm font-light text-lightColor"]) }}
>
    {{-- TODO: change name to dynimc name --}}
    <div>© {{ now()->year }} {{ $info->name }}.</div>
    <div class="flex justify-end gap-x-4">
        {{-- TODO: there is a problem when switching from a dropdown to onather --}}
        <x-shared.local-switcher location="bottom" />
        <livewire:currency-selector location="bottom" />
    </div>
    <img
        class="block w-[200px]"
        src="{{ asset("storage/images/payment-methods.webp") }}"
        alt="{{ __("store.Payment methods") }}"
    />
</div>
