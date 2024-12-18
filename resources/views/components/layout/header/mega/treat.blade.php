@props([
    /**@var\mixed*/"category",
])

<div class="menu-item order-3 translate-x-2 opacity-0">
    <h2 class="text-base font-bold">
        {{ $category->name }}
    </h2>
    @foreach (\App\Models\ProductType::all() as $type)
        <ul class="mt-1">
            <li>
                <a
                    href="{{ route("product.category", $type) }}"
                    class="mega-menu-link group inline-block"
                >
                    <span class="link-text text-[0.95rem]">
                        {{ $type->name }}
                    </span>
                </a>
            </li>
        </ul>
    @endforeach
</div>
