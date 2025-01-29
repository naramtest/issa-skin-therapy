@props([
    "item",
])
<tr class="border-b">
    <td class="border p-4">
        <div>
            <a
                class="text-blue-600"
                href="{{ route("product.show", ["product" => $item->purchasable->slug]) }}"
            >
                <p class="font-medium">
                    {{ $item->purchasable->name }}
                </p>
            </a>
            <span class="text-gray-600">× {{ $item->quantity }}</span>
        </div>
    </td>

    <td class="border p-4">
        <x-price :money="$item->getMoneySubtotal()" />
    </td>
</tr>
