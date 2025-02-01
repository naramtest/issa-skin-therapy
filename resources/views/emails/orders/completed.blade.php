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
                background-color: #28a745; /* Green for completed */
                color: #ffffff;
                border-radius: 20px;
                font-size: 14px;
                font-weight: bold;
                margin-bottom: 15px;
            }

            .status-section {
                padding: 30px 0;
                text-align: center;
                background-color: #f1f9f3; /* Light green background */
                border-radius: 6px;
                margin: 20px 0;
            }

            .feedback-section {
                margin: 30px 0;
                text-align: center;
                padding: 20px;
                background-color: #f8f9fa;
                border-radius: 6px;
            }

            .rating-stars {
                margin: 20px 0;
                font-size: 24px;
                color: #ffc107; /* Gold color for stars */
            }

            /* Override button color for completed state */
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
                color: #ffffff;
                text-decoration: none;
                border-radius: 4px;
                margin: 10px 0;
                font-size: 14px;
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

                <!-- Feedback Section -->
                <div class="feedback-section">
                    <h3>{{ __("store.How was your experience?") }}</h3>
                    <p>
                        {{ __("store.We would love to hear your feedback") }}
                    </p>
                    <div class="rating-stars">★★★★★</div>
                    <a href="{{ route("storefront.index") }}" class="button">
                        {{ __("store.Leave a Review") }}
                    </a>
                </div>

                @include("emails.orders.partials._footer")
            </div>
        </div>
    </body>
</html>
