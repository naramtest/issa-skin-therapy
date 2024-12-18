@props([
    "category",
    "loop",
])

<div @class(["menu-item translate-x-2 opacity-0", "order-last" => $loop == 2])>
    <h2 class="text-base font-bold">
        {{ $category->name }}
    </h2>
    @foreach ($category->products as $product)
        <ul class="mt-1">
            <li>
                <a
                    href="{{ route("product.show", $product) }}"
                    class="mega-menu-link group inline-block"
                >
                    <span class="link-text text-[0.95rem]">
                        {{ $product->name }}
                    </span>
                </a>
            </li>
        </ul>
    @endforeach
</div>
