@props([
    "name",
])

<x-dynamic-component
    {{
    $attributes->class([
        'max-w-2xl text-white hover:cursor-pointer  hover:text-gray-300
                hover:scale-90 transition-transform duration-300
                    ',
    ])
}}
    :component="'share.'.$name"
/>
