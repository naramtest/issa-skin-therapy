<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <title>{{ $siteName }} - Newsletter Verification</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                line-height: 1.6;
                max-width: 600px;
                margin: 0 auto;
                padding: 20px;
                color: #333;
            }

            .container {
                background-color: #f4f4f4;
                padding: 20px;
                border-radius: 5px;
            }

            .btn {
                display: inline-block;
                background-color: #007bff;
                color: white !important;
                padding: 10px 20px;
                text-decoration: none;
                border-radius: 5px;
                margin: 15px 0;
            }

            .footer {
                margin-top: 20px;
                font-size: 12px;
                color: #777;
                text-align: center;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <h2>{{ $siteName }} Newsletter</h2>

            {!! $messageBody !!}

            <div class="text-center">
                <a href="{{ $verificationLink }}" class="btn">
                    Verify Subscription
                </a>
            </div>

            <div class="footer">
                <p>
                    © {{ $siteName }} {{ date("Y") }} | All rights reserved
                </p>
                {{-- TODO: add unscbscribe link --}}
                {{-- <p> --}}
                {{-- If you no longer wish to receive these emails, please --}}
                {{-- <a href="{{ route("unsubscribe") }}">unsubscribe</a> --}}
                {{-- . --}}
                {{-- </p> --}}
            </div>
        </div>
    </body>
</html>
