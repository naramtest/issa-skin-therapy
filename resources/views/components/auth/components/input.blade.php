@props([
    "field",
    "placeholder",
    "label",
    "type" => "text",
    "isRequired",
])

<div {{ $attributes->class(["mb-4"]) }}>
    <label class="ms-1 inline-block text-[17px]" for="{{ $field }}">
        {{ $label }}
    </label>
    <input
        class="mt-1 block w-full rounded-lg border border-gray-300 px-2 py-2 placeholder:text-xs"
        type="{{ $type }}"
        name="{{ $field }}"
        id="{{ $field }}"
        placeholder="{{ $placeholder }}"
        value="{{ old($field) }}"
        @required($isRequired)
    />
    @error($field)
        <p class="ms-1 mt-1 text-sm text-red-500">
            {{ $message }}
        </p>
    @enderror
</div>
