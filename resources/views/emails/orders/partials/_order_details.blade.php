{{-- resources/views/emails/orders/partials/_order_details.blade.php --}}
<div class="order-details">
    <h2>{{ $translations["details"]["title"] }}</h2>
    <p>
        <strong>{{ $translations["details"]["number"] }}:</strong>
        {{ $order->order_number }}
    </p>
    <p>
        <strong>{{ $translations["details"]["date"] }}:</strong>
        {{ $order->created_at->format("F j, Y") }}
    </p>
    <p>
        <strong>{{ $translations["details"]["payment_status"] }}:</strong>
        {{ $order->payment_status->value }}
    </p>

    <table class="order-table">
        <thead>
            <tr>
                <th>{{ $translations["details"]["product"] }}</th>
                <th>{{ $translations["details"]["quantity"] }}</th>
                <th>{{ $translations["details"]["price"] }}</th>
                <th>{{ $translations["details"]["total"] }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($items as $item)
                <tr>
                    <td>{{ $item->purchasable->name }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ $item->money_unit_price }}</td>
                    <td>{{ $item->getMoneySubtotal() }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3" style="text-align: right">
                    <strong>
                        {{ $translations["details"]["subtotal"] }}:
                    </strong>
                </td>
                <td>{{ $order->getMoneySubtotal() }}</td>
            </tr>
            <tr>
                <td colspan="3" style="text-align: right">
                    <strong>
                        {{ $translations["details"]["shipping"] }}:
                    </strong>
                </td>
                <td>{{ $order->money_shipping_cost }}</td>
            </tr>
            <tr>
                <td colspan="3" style="text-align: right">
                    <strong>{{ $translations["details"]["total"] }}:</strong>
                </td>
                <td>{{ $order->getMoneyTotal() }}</td>
            </tr>
        </tfoot>
    </table>
</div>
