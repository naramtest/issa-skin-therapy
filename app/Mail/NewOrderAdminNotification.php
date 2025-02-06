<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewOrderAdminNotification extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(public Order $order)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: __(__("store.New Order Received"), [
                "order_number" => $this->order->order_number,
            ])
        );
    }

    public function content(): Content
    {
        return new Content(
            view: "emails.admin.new-order",
            with: [
                "order" => $this->order,
                "customer" => $this->order->customer,
                "billingAddress" => $this->order->billingAddress,
                "shippingAddress" => $this->order->shippingAddress,
                "items" => $this->order->items,
            ]
        );
    }

    public function attachments(): array
    {
        if (count($this->order->invoices)) {
            return [
                $this->order->invoices()->latest()->first()->toMailAttachment(),
            ];
        }
        return [];
    }
}
