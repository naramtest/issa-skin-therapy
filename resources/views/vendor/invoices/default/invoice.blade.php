{{-- TODO: translate --}}

@php
    $order = \App\Models\Order::where("order_number", $invoice->description)
        ->with(["items", "couponUsage"])
        ->first();
    $discount = $order->couponUsage
        ? app(\App\Services\Coupon\CouponService::class)
            ->calculateDiscount($order->couponUsage->coupon, $order->getMoneySubtotal())
            ->getAmount()
        : null;
@endphp

<!DOCTYPE html>
<html lang="en">
    <head>
        <title>{{ $invoice->name }}</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        @include("invoices::default.style")
    </head>
    <body>
        <div style="padding: 4rem">
            <!-- Business Header -->
            <img
                style="display: inline; width: 50%; height: 160px"
                class="inline w-[2/3]"
                src="{{ $invoice->getLogo() }}"
                alt=""
            />

            <div style="display: inline-block; padding-left: 60px">
                <h1 style="font-size: 16px">
                    {{ config("app.name_" . App::getLocale()) }}
                </h1>
                <p>VitaDerm FZE</p>
                <p>Dubai</p>
                <p>U.A.E</p>
                <p>00971585957616</p>
                <p>info@issaskintherapy.com</p>
                <p>www.issaskintherapy.com</p>
            </div>

            <h2 style="margin-top: 30px; margin-bottom: 0">INVOICE</h2>

            <!-- Buyer & Shipping Details -->
            <table style="margin-top: 0" class="mb-6 w-full">
                <tr>
                    <td class="align-top" width="50%">
                        <strong>Bill To:</strong>
                        <p>{{ $invoice->buyer["name"] }}</p>
                        <p>{{ $invoice->buyer["address"]["street"] }}</p>
                        <p>
                            {{ $invoice->buyer["address"]["city"] }},
                            {{ $invoice->buyer["address"]["state"] }}
                        </p>
                        <p>{{ $invoice->buyer["address"]["postal_code"] }}</p>
                        <p>{{ $invoice->buyer["email"] }}</p>
                        <p>{{ $invoice->buyer["phone_number"] }}</p>
                    </td>
                    <td class="p-0 align-top" width="50%">
                        <strong>Ship To:</strong>
                        <p>{{ $invoice->buyer["name"] }}</p>
                        <p>{{ $invoice->buyer["address"]["street"] }}</p>
                        <p>
                            {{ $invoice->buyer["address"]["city"] }},
                            {{ $invoice->buyer["address"]["state"] }}
                        </p>
                        <p>{{ $invoice->buyer["address"]["postal_code"] }}</p>
                    </td>
                </tr>
            </table>

            <!-- Invoice Details -->
            <table class="mb-6 w-full">
                <tr>
                    <td>
                        <strong>Invoice Number:</strong>
                        {{ $invoice->serial_number }}
                    </td>
                    <td>
                        <strong>Invoice Date:</strong>
                        {{ $invoice->created_at->format("F d, Y") }}
                    </td>
                </tr>
                <tr>
                    <td>
                        <strong>Order Number:</strong>
                        {{ $order->order_number }}
                    </td>
                    <td>
                        <strong>Order Date:</strong>
                        {{ $order->created_at->format("F d, Y") }}
                    </td>
                </tr>
                <tr>
                    <td>
                        <strong>Payment Method:</strong>
                        {{ strtoupper($order->payment_method_details["brand"] ?? "") }}
                        @if (isset($order->payment_method_details["last4"]))
                            {{ __("store.ENDING IN") }}
                            {{ $order->payment_method_details["last4"] }}
                        @endif
                    </td>
                </tr>
            </table>

            <!-- Product Table -->
            <table class="mb-5 w-full">
                <thead>
                    <tr>
                        <th class="border-b p-2 text-left">Product</th>
                        <th class="border-b p-2 text-left">Quantity</th>
                        <th class="border-b p-2 text-left">Price</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($order->items as $item)
                        <tr>
                            <td class="border-b p-2 align-top">
                                <strong>{{ $item->purchasable->name }}</strong>

                                <p style="font-size: 12px">
                                    Weight:
                                    {{ $item->purchasable->weight ?? "N/A" }}kg
                                </p>
                            </td>
                            <td class="border-b p-2 align-top">
                                {{ $item->quantity }}
                            </td>
                            <td class="border-b p-2 align-top">
                                <x-price-with-currency
                                    :money="$item->unit_price"
                                    :currency="$order->currency_code"
                                />
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Summary -->
            <table class="mb-6 w-full">
                <tr>
                    <td><strong>Subtotal</strong></td>
                    <td class="text-right">
                        <x-price-with-currency
                            :money="$order->subtotal"
                            :currency="$order->currency_code"
                        />
                    </td>
                </tr>
                @if ($discount)
                    <tr>
                        <td><strong>Discount</strong></td>
                        <td class="text-right">
                            -
                            <x-price-with-currency
                                :money="$discount"
                                :currency="$order->currency_code"
                            />
                        </td>
                    </tr>
                @endif

                <tr>
                    <td><strong>Shipping</strong></td>
                    <td class="text-right">
                        @if ($order->shipping_cost > 0)
                            <x-price-with-currency
                                :money="$order->shipping_cost"
                                :currency="$order->currency_code"
                            />
                        @else
                                Free Shipping
                        @endif
                    </td>
                </tr>
                <tr>
                    <td><strong>Total</strong></td>
                    <td class="text-right">
                        <strong>
                            <x-price-with-currency
                                :money="$order->total"
                                :currency="$order->currency_code"
                            />
                        </strong>
                    </td>
                </tr>
            </table>

            <!-- Terms & Conditions -->
            <p>
                1) We reserve the right to change prices, terms and conditions
                without notice.
            </p>
            <p>
                2) All orders must be pre-paid by money order, or credit card.
            </p>
            <p>3) All products and services are subject to availability.</p>
            <p>
                4) Returns and exchanges are accepted within 7 days of delivery.
            </p>
            <p>
                5) For full terms and conditions, please visit:
                <a href="{{ route("terms.index") }}">
                    issaskintherapy.com/terms-and-conditions/
                </a>
            </p>
        </div>
    </body>
</html>
