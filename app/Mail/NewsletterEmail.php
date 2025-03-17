<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewsletterEmail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(private readonly string $verificationLink)
    {
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: __("store.Please Confirm Your Newsletter Subscription")
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: "emails.newsletter.verification",
            with: [
                "messageBody" => $this->generateMessageBody(),
                "siteName" => config("app.name"),
                "verificationLink" => $this->verificationLink,
            ]
        );
    }

    /**
     * Generate the verification message body
     */
    protected function generateMessageBody(): string
    {
        return __("store.verification_message", [
            "link" => $this->verificationLink,
            "siteName" => config("app.name"),
        ]);
    }

    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function attachments(): array
    {
        return [];
    }
}
