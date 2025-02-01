<?php

namespace App\Mail;

use App\Enums\Checkout\OrderStatus;
use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderStatusMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public Order $order,
        public OrderStatus $status,
        public ?string $additionalMessage = null
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(subject: $this->getSubject());
    }

    protected function getSubject(): string
    {
        return trans("emails.orders.subject." . $this->status->value, [
            "order" => $this->order->order_number,
        ]);
    }

    public function content(): Content
    {
        return new Content(
            view: $this->getTemplate(),
            with: [
                "order" => $this->order,
                "customer" => $this->order->customer,
                "billingAddress" => $this->order->billingAddress,
                "shippingAddress" => $this->order->shippingAddress,
                "items" => $this->order->items,
                "additionalMessage" => $this->additionalMessage,
                "translations" => $this->getTranslations(),
            ]
        );
    }

    protected function getTemplate(): string
    {
        return match ($this->status) {
            OrderStatus::PROCESSING => "emails.orders.processing",
            OrderStatus::COMPLETED => "emails.orders.completed",
            OrderStatus::CANCELLED => "emails.orders.cancelled",
            default => "emails.orders.status-update",
        };
    }

    protected function getTranslations(): array
    {
        return [
            "status" => trans("emails.orders.status." . $this->status->value),
            "common" => trans("emails.orders.status.common"),
            "details" => trans("emails.orders.order_details"),
        ];
    }

    public function attachments(): array
    {
        $attachments = [];

        if (
            $this->status === OrderStatus::COMPLETED &&
            count($this->order->invoices)
        ) {
            $attachments[] = $this->order
                ->invoices()
                ->latest()
                ->first()
                ->toMailAttachment();
        }

        return $attachments;
    }
}
