@props([
    "label",
    "required" => false,
    "field",
    "type" => "text",
    "placeHolder",
    "options",
])

<div {{ $attributes->except(["wire:model", "wire:model.live"]) }}>
    <label
        for="{{ $field }}"
        class="mb-1 block text-[.9rem] font-[700] text-[#69727d]"
    >
        <span>{{ $label }}</span>
        @if ($required)
            <span class="text-red-500">*</span>
        @endif
    </label>
    <select
        class="w-full rounded-[11px] border-none bg-[#F4F4F4] px-7 py-5 text-sm text-[#69727d] focus:outline-none"
        name="{{ $field }}"
        @required($required)
        id="{{ $field }}"
        {{ $attributes->only(["wire:model", "wire:model.live"]) }}
    >
        <option value="">{{ __("Select") }} {{ $label }}</option>
        @foreach ($options as $option)
            <option value="{{ $option }}">{{ $option }}</option>
        @endforeach
    </select>
    @error($field)
        <p class="ms-1 mt-1 text-sm text-red-500">
            {{ $message }}
        </p>
    @enderror
</div>
