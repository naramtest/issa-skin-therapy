<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    "postmark" => [
        "token" => env("POSTMARK_TOKEN"),
    ],

    "ses" => [
        "key" => env("AWS_ACCESS_KEY_ID"),
        "secret" => env("AWS_SECRET_ACCESS_KEY"),
        "region" => env("AWS_DEFAULT_REGION", "us-east-1"),
    ],

    "resend" => [
        "key" => env("RESEND_KEY"),
    ],

    "slack" => [
        "notifications" => [
            "bot_user_oauth_token" => env("SLACK_BOT_USER_OAUTH_TOKEN"),
            "channel" => env("SLACK_BOT_USER_DEFAULT_CHANNEL"),
        ],
    ],

    "exchange_rates_data" => env("EXCHANGE_RATES_DATA_API_KEY"),
    "fixer" => env("FIXER_API_KEY"),
    "currency_layer" => env("CURRENCY_LAYER_API_KEY"),

    "stripe" => [
        "api_key" => env("STRIPE_API_KEY"),
        "secret_key" => env("STRIPE_SECRET_KEY"),
        "webhook_secret" => env("STRIPE_WEBHOOK_SECRET"),
    ],

    "dhl" => [
        "api_key" => env("DHL_API_KEY"),
    ],

    "tabby" => [
        "public_key" => env("TABBY_PUBLIC_KEY"),
        "secret_key" => env("TABBY_SECRET_KEY"),
        "merchant_code" => env("TABBY_MERCHANT_CODE"),
        "merchant_code_sauce" => env("TABBY_MERCHANT_CODE_SAU"),
    ],

    "facebook" => [
        "facebook_pixel_id" => env("FACEBOOK_PIXEL_ID"),
    ],
    "cloudflare" => [
        "site_key" => env("CLOUDFLARE_TURNSTILE_SITE_KEY"),
        "secret_key" => env("CLOUDFLARE_TURNSTILE_SECRET_KEY"),
    ],
];
