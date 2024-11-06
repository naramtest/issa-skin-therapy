@props([
    "name",
    "id",
    "isActive" => false,
])

<li
    wire:click="selectCategory({{ $id }})"
    @class([
        " swiper-slide px-8 !w-fit rounded-[50px]  py-3 text-center ",
        "bg-[#FAFAFA] hover:cursor-pointer hover:bg-darkColor hover:text-white" => ! $isActive,
        "bg-darkColor text-white" => $isActive,
    ])
>
    {{ $name }}
</li>
