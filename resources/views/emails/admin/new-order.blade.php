<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <title>{{ __("store.New Order Notification") }}</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                line-height: 1.6;
                color: #333;
            }

            .container {
                max-width: 600px;
                margin: 0 auto;
                padding: 20px;
            }

            .header {
                text-align: center;
                margin-bottom: 30px;
                background-color: #f8f9fa;
                padding: 20px;
                border-radius: 5px;
            }

            .alert {
                background-color: #f8d7da;
                color: #721c24;
                padding: 10px;
                margin-bottom: 20px;
                border-radius: 5px;
            }

            .customer-info {
                margin-bottom: 30px;
                padding: 15px;
                background-color: #f8f9fa;
                border-radius: 5px;
            }

            .items-table {
                width: 100%;
                border-collapse: collapse;
                margin-bottom: 30px;
            }

            .items-table th,
            .items-table td {
                padding: 10px;
                border-bottom: 1px solid #ddd;
                text-align: left;
            }

            .total-section {
                margin-top: 20px;
                background-color: #f8f9fa;
                padding: 15px;
                border-radius: 5px;
            }

            .action-button {
                display: inline-block;
                padding: 10px 20px;
                background-color: #007bff;
                color: white;
                text-decoration: none;
                border-radius: 5px;
                margin-top: 20px;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="header">
                <h1>{{ __("store.New Order Received") }}</h1>
                <p>
                    {{ __("store.Order Number") }}: {{ $order->order_number }}
                </p>
            </div>

            <div class="alert">
                {{ __("store.This order requires your attention") }}
            </div>

            <div class="customer-info">
                <h2>{{ __("store.Customer Information") }}</h2>
                <p>
                    <strong>{{ __("store.Name") }}:</strong>
                    {{ $customer->full_name }}
                </p>
                <p>
                    <strong>{{ __("store.Email") }}:</strong>
                    {{ $customer->email }}
                </p>
                <p>
                    <strong class="capitalize">
                        {{ __("store.phone") }}:
                    </strong>
                    {{ $billingAddress->phone }}
                </p>
            </div>

            <div style="margin-bottom: 30px">
                <div style="width: 48%; float: left">
                    <h3>{{ __("store.Billing Address") }}</h3>
                    <p>
                        {{ $billingAddress->full_name }}
                        <br />
                        {{ $billingAddress->address }}
                        <br />
                        {{ $billingAddress->city }},
                        {{ $billingAddress->state }}
                        <br />
                        {{ $billingAddress->postal_code }}
                        <br />
                        {{ $billingAddress->country }}
                    </p>
                </div>
                <div style="width: 48%; float: right">
                    <h3>{{ __("store.Shipping Address") }}</h3>
                    <p>
                        {{ $shippingAddress->full_name }}
                        <br />
                        {{ $shippingAddress->address }}
                        <br />
                        {{ $shippingAddress->city }},
                        {{ $shippingAddress->state }}
                        <br />
                        {{ $shippingAddress->postal_code }}
                        <br />
                        {{ $shippingAddress->country }}
                    </p>
                </div>
                <div style="clear: both"></div>
            </div>

            <table class="items-table">
                <thead>
                    <tr>
                        <th>{{ __("dashboard.Product") }}</th>
                        <th>{{ __("dashboard.Quantity") }}</th>
                        <th>{{ __("store.Price") }}</th>
                        <th>{{ __("store.Total") }}</th>
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
            </table>

            <div class="total-section">
                <p>
                    <strong>{{ __("store.Subtotal") }}:</strong>
                    {{ $order->getMoneySubtotal() }}
                </p>
                <p>
                    <strong>{{ __("store.Shipping") }}:</strong>
                    {{ $order->money_shipping_cost }}
                </p>
                <p>
                    <strong>{{ __("store.Total") }}:</strong>
                    {{ $order->getMoneyTotal() }}
                </p>
                <p>
                    <strong>{{ __("store.Payment Status") }}:</strong>
                    {{ $order->payment_status->value }}
                </p>
                <p>
                    <strong>{{ __("store.Order Status") }}:</strong>
                    {{ $order->status->value }}
                </p>
            </div>

            <div style="text-align: center; margin-top: 30px">
                <a
                    href="{{ config("app.url") }}/admin/orders/{{ $order->id }}"
                    class="action-button"
                >
                    {{ __("store.View Order Details") }}
                </a>
            </div>
        </div>
    </body>
</html>
