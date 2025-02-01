{{-- resources/views/emails/orders/processing.blade.php --}}
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>{{ $translations["status"]["title"] }}</title>
        @include("emails.orders.partials._styles")
        <style>
            /* Processing-specific styles */
            .status-badge {
                display: inline-block;
                padding: 8px 16px;
                background-color: #007bff;
                color: #ffffff;
                border-radius: 20px;
                font-size: 14px;
                font-weight: bold;
                margin-bottom: 15px;
            }

            .status-section {
                padding: 30px 0;
                text-align: center;
                background-color: #f8f9fa;
                border-radius: 6px;
                margin: 20px 0;
            }

            .next-steps {
                margin: 30px 0;
                text-align: center;
                padding: 20px;
                background-color: #f8f9fa;
                border-radius: 6px;
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
                    <p>{{ $translations["status"]["shipping_note"] }}</p>
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

                <div class="next-steps">
                    <h3>{{ $translations["status"]["next_steps"] }}</h3>
                    <p>{{ $translations["status"]["shipping_note"] }}</p>
                    <a
                        href="{{ route("order.tracking", ["number" => $order->order_number]) }}"
                        class="button"
                    >
                        {{ __("store.Track Your Order") }}
                    </a>
                </div>

                @include("emails.orders.partials._footer")
            </div>
        </div>
    </body>
</html>
