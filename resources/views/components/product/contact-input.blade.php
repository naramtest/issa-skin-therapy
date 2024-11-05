@props([
    "field",
    "placeholder",
    "type" => "text",
])

<div {{ $attributes->class([" rounded-lg bg-[#3b3b3b] px-3 py-3"]) }}>
    <label class="w-full" for="{{ $field }}">
        <input
            class="w-full rounded-lg border-none bg-transparent text-sm focus-visible:outline-0 focus-visible:ring-0"
            type="{{ $type }}"
            name="{{ $field }}"
            id="{{ $field }}"
            placeholder="{{ $placeholder }}"
            value="{{ old($field) }}"
        />
    </label>
</div>
