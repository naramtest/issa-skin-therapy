<!DOCTYPE html>
<html>
    <head>
        <title>New Contact Submission</title>
    </head>
    <body>
        <h1>New Contact Form Submission</h1>
        <p>
            <strong>Name:</strong>
            {{ $contactMessage->name }}
        </p>
        <p>
            <strong>Email:</strong>
            {{ $contactMessage->email }}
        </p>
        <p>
            <strong>Phone:</strong>
            {{ $contactMessage->phone }}
        </p>
        <p>
            <strong>Subject:</strong>
            {{ $contactMessage->subject }}
        </p>
        <p><strong>Message:</strong></p>
        <p>{{ $contactMessage->message }}</p>
        <p>
            <small>
                Submitted on:
                {{ $contactMessage->created_at->format("F j, Y, g:i a") }}
            </small>
        </p>
    </body>
</html>
