{{-- resources/views/emails/orders/completed.blade.php --}}
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>{{ $translations["status"]["title"] }}</title>
        @include("emails.orders.partials._styles")
        <style>
            /* Completed-specific styles */
            .status-badge {
                display: inline-block;
                padding: 8px 16px;
                background-color: #28a745;
                color: #ffffff;
                border-radius: 20px;
                font-size: 14px;
                font-weight: bold;
                margin-bottom: 15px;
            }

            .status-section {
                padding: 30px 0;
                text-align: center;
                background-color: #f1f9f3;
                border-radius: 6px;
                margin: 20px 0;
            }

            .tracking-section {
                margin: 30px 0;
                text-align: center;
                padding: 20px;
                background-color: #f8f9fa;
                border-radius: 6px;
            }

            .tracking-number {
                font-size: 16px;
                font-weight: bold;
                color: #333;
                margin: 10px 0;
                padding: 10px;
                background: #fff;
                border: 1px dashed #ccc;
                border-radius: 4px;
                display: inline-block;
            }

            /* Button styles */
            .button {
                background-color: #28a745;
            }

            .button:hover {
                background-color: #218838;
            }

            .download-invoice {
                display: inline-block;
                padding: 10px 20px;
                background-color: #6c757d;
                color: #ffffff !important;
                text-decoration: none;
                border-radius: 4px;
                margin: 10px 0;
                font-size: 14px;
            }

            .track-button {
                display: inline-block;
                padding: 12px 24px;
                background-color: #007bff;
                color: #ffffff !important;
                text-decoration: none;
                border-radius: 4px;
                font-weight: bold;
                margin: 10px 5px;
            }

            .track-button:hover {
                background-color: #0056b3;
            }
        </style>
    </head>
    <body>
        <div class="email-wrapper">
            <div class="email-container">
                @include("emails.orders.partials._header")

                <div class="status-section">
                    <span class="status-badge">
                        {{ $translations["status"]["message"] }}
                    </span>
                    <p>{{ $translations["status"]["feedback"] }}</p>

                    <!-- Completed Check Mark Icon -->
                    <div style="margin: 20px 0">
                        <svg
                            width="64"
                            height="64"
                            viewBox="0 0 64 64"
                            fill="none"
                            xmlns="http://www.w3.org/2000/svg"
                        >
                            <circle cx="32" cy="32" r="32" fill="#28a745" />
                            <path
                                d="M20 32L28 40L44 24"
                                stroke="white"
                                stroke-width="4"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            />
                        </svg>
                    </div>
                </div>

                <!-- Tracking Section -->
                <div class="tracking-section">
                    <h3>{{ __("store.Track Your Order") }}</h3>
                    <p>{{ __("store.Your tracking number is") }}:</p>
                    <div class="tracking-number">
                        {{ $order->shippingOrder?->tracking_number ?? __("store.Not available yet") }}
                    </div>
                    <div style="margin-top: 15px">
                        @if ($order->shippingOrder?->tracking_url)
                            <a
                                href="{{ $order->shippingOrder?->tracking_url }}"
                                class="track-button"
                            >
                                {{ __("store.Track Package") }}
                            </a>
                        @endif
                    </div>
                </div>

                @include("emails.orders.partials._order_details")

                @if ($additionalMessage)
                    <div
                        style="
                            margin: 20px 0;
                            padding: 15px;
                            background-color: #f8f9fa;
                            border-radius: 4px;
                        "
                    >
                        <h3 style="margin-top: 0; color: #333333">
                            {{ $translations["common"]["additional_info"] }}
                        </h3>
                        <p style="margin-bottom: 0; color: #666666">
                            {{ $additionalMessage }}
                        </p>
                    </div>
                @endif

                <!-- Download Invoice Section -->
                <div style="text-align: center; margin: 20px 0">
                    <a
                        href="{{ route("orders.invoice.download", $order) }}"
                        class="download-invoice"
                    >
                        {{ __("emails. Download Invoice") }}
                    </a>
                </div>

                @include("emails.orders.partials._footer")
            </div>
        </div>
    </body>
</html>
