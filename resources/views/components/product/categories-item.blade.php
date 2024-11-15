@props([
    "name",
    "id",
    "isActive" => false,
])

<li
    wire:key="category-{{ $id }}"
    @click="selectCategory({{ $id }})"
    @class([
        "swiper-slide !w-fit cursor-pointer rounded-[50px] px-8 py-3 text-center transition-all duration-300",
        "bg-[#FAFAFA] hover:bg-darkColor hover:text-white" => ! $isActive,
        "bg-darkColor text-white" => $isActive,
    ])
>
    {{ $name }}
</li>
