@props([
    "label",
    "required" => false,
    "field",
    "type" => "text",
    "placeHolder",
])

<div {{ $attributes }}>
    <label class="mb-1 block font-[700] text-[#69727d]" for="{{ $field }}">
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
        required="{{ $required }}"
        placeholder="{{ $placeHolder }}"
        value="{{ old($field) }}"
    />
</div>
