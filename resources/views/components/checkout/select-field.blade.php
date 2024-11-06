@props([
    "label",
    "required" => false,
    "field",
    "type" => "text",
    "placeHolder",
    "options",
])

{{-- TODO: add real data , and search , style the options --}}

<div {{ $attributes }}>
    <label for="{{ $field }}" class="mb-1 block font-[700] text-[#69727d]">
        <span>{{ $label }}</span>
        @if ($required)
            <span class="text-red-500">*</span>
        @endif
    </label>
    <select
        class="w-full rounded-[11px] border-none bg-[#F4F4F4] px-7 py-5 text-sm text-[#69727d] focus:outline-none"
        name="{{ $field }}"
        id="{{ $field }}"
    >
        @foreach ($options as $option)
            <option>{{ $option }}</option>
        @endforeach
    </select>
</div>
