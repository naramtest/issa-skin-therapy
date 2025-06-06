<?php

namespace App\Contracts;

use App\Models\Order;

interface PaymentServiceInterface
{
    /**
     * Create a payment intent for the order
     */
    public function createPaymentIntent(Order $order): array;

    /**
     * Confirm a payment intent
     */
    public function confirmPayment(
        Order $order,
        string $paymentIntentId
    ): array;

    /**
     * Calculate payment amount with fees
     */
    public function calculatePaymentAmount(Order $order): int;

    public function updateOrder(Order $order, string $id): bool;

    public function processPayment(Order $order): array;

    public function getPaymentStatus(string $paymentId): array;
}
