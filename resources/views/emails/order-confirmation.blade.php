<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <title>{{ __("store.Order Confirmation1") }}</title>
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
            }

            .order-info {
                margin-bottom: 30px;
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
            }

            .address-section {
                margin-bottom: 30px;
            }

            .footer {
                text-align: center;
                margin-top: 30px;
                padding-top: 20px;
                border-top: 1px solid #ddd;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="header">
                <h1>{{ __("store.Order Confirmation1") }}</h1>
                <p>{{ __("store.Thank you for your order") }}!</p>
            </div>

            <div class="order-info">
                <h2>{{ __("store.Order Details") }}</h2>
                <p>
                    <strong>{{ __("store.Order Number") }}:</strong>
                    {{ $order->order_number }}
                </p>
                <p>
                    <strong>{{ __("store.Order Date") }}:</strong>
                    {{ $order->created_at->format("F j, Y") }}
                </p>
                <p>
                    <strong>{{ __("store.Payment Status") }}:</strong>
                    {{ $order->payment_status->value }}
                </p>
            </div>

            <div class="address-section">
                <div style="width: 48%; float: left">
                    <h3>{{ __("store.Billing Address") }}</h3>
                    <p>
                        {{ $billingAddress->first_name }}
                        {{ $billingAddress->last_name }}
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
                        {{ $shippingAddress->first_name }}
                        {{ $shippingAddress->last_name }}
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
                            <td>{{ $item->getMoneyUnitPrice() }}</td>
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
                    {{ $order->getMoneyShippingCost() }}
                </p>
                <p>
                    <strong>{{ __("store.Total") }}:</strong>
                    {{ $order->getMoneyTotal() }}
                </p>
            </div>

            <div class="footer">
                <p>
                    {{ __("store.If you have any questions about your order, please contact our customer service") }}.
                </p>
            </div>
        </div>
    </body>
</html>
