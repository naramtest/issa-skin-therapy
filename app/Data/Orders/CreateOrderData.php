<?php

namespace App\Data\Orders;

use App\Enums\Checkout\OrderStatus;
use App\Enums\Checkout\PaymentStatus;

readonly class CreateOrderData
{
    public function __construct(
        public string $customerId,
        public string $email,
        public int $billingAddressId,
        public int $shippingAddressId,
        public OrderStatus $status,
        public PaymentStatus $paymentStatus,
        public ?string $shippingMethod,
        public int $subtotal,
        public ?int $shippingCost,
        public int $total,
        public ?string $notes,
        public array $cartItems
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            customerId: $data["customer_id"],
            email: $data["email"],
            billingAddressId: $data["billing_address_id"],
            shippingAddressId: $data["shipping_address_id"],
            status: $data["status"],
            paymentStatus: $data["payment_status"],
            shippingMethod: $data["shipping_method"] ?? null,
            subtotal: $data["subtotal"],
            shippingCost: $data["shipping_cost"] ?? null,
            total: $data["total"],
            notes: $data["notes"] ?? null,
            cartItems: $data["cart_items"]
        );
    }
}
