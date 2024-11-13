@props([
    "field",
    "placeholder",
    "label",
    "type" => "text",
])

<div {{ $attributes->class(["rounded-[10px] bg-[#F4F4F4] px-5 py-[1px]"]) }}>
    <label class="text-sm" for="name">{{ $label }}</label>
    <input
        class="block bg-transparent pb-3 pt-1 text-sm placeholder:text-sm focus:outline-none"
        type="{{ $type }}"
        name="{{ $field }}"
        id="{{ $field }}"
        placeholder="{{ $placeholder }}"
        value="{{ old($field) }}"
    />
</div>
