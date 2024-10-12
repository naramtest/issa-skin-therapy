@props([
    "name",
    "url",
])
<a
    rel="nofollow noopener"
    href="{{ $url }}"
    aria-label="{{ $name }}"
    target="_blank"
>
    <x-dynamic-component
        {{
    $attributes->class([
        'max-w-2xl dark:text-white hover:cursor-pointer
                        hover:scale-90 transition-transform duration-300
                    ',
    ])
}}
        :component="'share.'.$name"
    />
</a>
