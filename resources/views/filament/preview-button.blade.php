@php
    $route = is_callable($route) ? $route($this) : $route;
@endphp

<a
    style="
        --c-400: var(--primary-400);
        --c-500: var(--primary-500);
        --c-600: var(--primary-600);
    "
    class="fi-btn fi-color-custom fi-btn-color-primary fi-size-md fi-btn-size-md bg-custom-600 hover:bg-custom-500 focus-visible:ring-custom-500/50 dark:bg-custom-500 dark:hover:bg-custom-400 dark:focus-visible:ring-custom-400/50 fi-ac-action fi-ac-btn-action relative inline-grid grid-flow-col items-center justify-center gap-1.5 rounded-lg px-3 py-2 text-sm font-semibold text-white shadow-sm outline-none transition duration-75 focus-visible:ring-2"
    target="_blank"
    wire:click="saveBeforePreview()"
    href="{{ $route }}"
>
    {{-- TODO: add label here --}}
    Preview
</a>
