@props([
    "label",
    "required" => false,
    "field",
    "type" => "text",
    "placeHolder",
    "helperText" => null,
    "error",
])

<div
    {{ $attributes->except(["wire:model", "wire:model.live", "wire:model.blur", "wire:model.defer"]) }}
>
    <label
        class="mb-1 block text-[.9rem] font-[700] text-[#69727d]"
        for="{{ $field }}"
    >
        {{ $label }}
        @if ($required)
            <span class="text-red-500">*</span>
        @endif
    </label>
    <input
        type="{{ $type }}"
        class="w-full rounded-[11px] border-none bg-[#F4F4F4] px-7 py-5 text-sm text-[#69727d] focus:outline-none"
        name="{{ $field }}"
        id="{{ $field }}"
        @required($required)
        placeholder="{{ $placeHolder }}"
        {{ $attributes->only(["wire:model", "wire:model.live", "wire:model.blur", "wire:model.defer"]) }}
    />
    @error($field)
        <p class="ms-1 mt-1 text-sm text-red-500">
            {{ $message }}
        </p>
    @enderror

    @if ($helperText)
        <p class="ms-1 mt-1 text-xs text-[#969595]">
            {!! $helperText !!}
        </p>
    @endif
</div>
