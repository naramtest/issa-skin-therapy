@props([
    "field",
    "placeholder",
])

<div {{ $attributes->class([" rounded-lg bg-[#3b3b3b] px-3 py-3"]) }}>
    <label class="w-full" for="{{ $field }}">
        <textarea
            class="w-full rounded-lg border-none bg-transparent text-sm focus-visible:outline-0 focus-visible:ring-0"
            name="{{ $field }}"
            id="{{ $field }}"
            placeholder="{{ $placeholder }}"
            value="{{ old($field) }}"
            rows="4"
        ></textarea>
    </label>
</div>
