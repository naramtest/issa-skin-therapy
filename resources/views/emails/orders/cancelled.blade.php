{{-- resources/views/emails/orders/cancelled.blade.php --}}
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>{{ $translations["status"]["title"] }}</title>
        @include("emails.orders.partials._styles")
        <style>
            /* Cancelled-specific styles */
            .status-badge {
                display: inline-block;
                padding: 8px 16px;
                background-color: #dc3545; /* Red for cancelled */
                color: #ffffff;
                border-radius: 20px;
                font-size: 14px;
                font-weight: bold;
                margin-bottom: 15px;
            }

            .status-section {
                padding: 30px 0;
                text-align: center;
                background-color: #fdf2f3; /* Light red background */
                border-radius: 6px;
                margin: 20px 0;
            }

            .support-section {
                margin: 30px 0;
                text-align: center;
                padding: 20px;
                background-color: #f8f9fa;
                border-radius: 6px;
            }

            /* Override button color for cancelled state */
            .button {
                background-color: #6c757d; /* Gray for cancelled state */
            }

            .button:hover {
                background-color: #5a6268;
            }

            .reason-box {
                margin: 20px 0;
                padding: 15px;
                background-color: #f8f9fa;
                border-radius: 4px;
                border-left: 4px solid #dc3545;
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

                    <!-- Cancelled Icon -->
                    <div style="margin: 20px 0">
                        <svg
                            width="64"
                            height="64"
                            viewBox="0 0 64 64"
                            fill="none"
                            xmlns="http://www.w3.org/2000/svg"
                        >
                            <circle cx="32" cy="32" r="32" fill="#dc3545" />
                            <path
                                d="M24 24L40 40M40 24L24 40"
                                stroke="white"
                                stroke-width="4"
                                stroke-linecap="round"
                            />
                        </svg>
                    </div>
                </div>

                <!-- Cancellation Reason -->
                @if ($additionalMessage)
                    <div class="reason-box">
                        <h3 style="margin-top: 0; color: #333333">
                            {{ $translations["status"]["reason_intro"] }}
                        </h3>
                        <p style="margin-bottom: 0; color: #666666">
                            {{ $additionalMessage }}
                        </p>
                    </div>
                @endif

                @include("emails.orders.partials._order_details")

                <!-- Support Section -->
                <div class="support-section">
                    <h3>{{ __("emails.Need Help?") }}</h3>
                    <p>{{ $translations["status"]["contact_support"] }}</p>
                    <a href="{{ route("contact.index") }}" class="button">
                        {{ __("store.Contact Support") }}
                    </a>

                    <!-- Alternative Products -->
                    <div style="margin-top: 20px">
                        <h3>{{ __("store.Looking for alternatives?") }}</h3>
                        <p>
                            {{ __("emails.Browse our collection for similar products") }}
                        </p>
                        <a href="{{ route("shop.index") }}" class="button">
                            {{ __("store.Continue Shopping") }}
                        </a>
                    </div>
                </div>

                <!-- Refund Information (if applicable) -->
                @if ($order->payment_status === \App\Enums\Checkout\PaymentStatus::REFUNDED)
                    <div
                        style="
                            margin: 20px 0;
                            padding: 20px;
                            background-color: #e9ecef;
                            border-radius: 6px;
                        "
                    >
                        <h3>{{ __("store.Refund Information") }}</h3>
                        <p>
                            {{ __("store.Your refund has been processed and should be reflected in your account within 5-7 business days") }}
                            .
                        </p>
                        <p style="font-size: 14px; color: #666666">
                            {{ __("emails.Refund Reference") }}:
                            {{ $order->payment_intent_id }}
                        </p>
                    </div>
                @endif

                @include("emails.orders.partials._footer")
            </div>
        </div>
    </body>
</html>
