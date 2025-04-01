<?php

namespace App\Traits\Payment;

use App\Enums\Checkout\PaymentMethod;
use App\Models\Order;
use Livewire\Attributes\On;

trait WithPayment
{
    use WithTabbyPayment;
    use WithStripePayment;

    #[On("payment-error")]
    public function setPaymentError($error): void
    {
        $this->error = $error;
        $this->processing = false;
    }

    public function oldOrderExists(): array
    {
        $order = Order::find($this->currentOrderId);
        try {
            if ($this->form->payment_method == PaymentMethod::CARD->value) {
                $paymentData = $this->stripeOldOrderExits($order);
                $this->dispatch(
                    "payment-ready",
                    clientSecret: $paymentData["clientSecret"]
                );
                return ["success" => true];
            }
            return $this->tabbyPaymentService->processPayment($order);
        } catch (\Exception) {
            return ["success" => false];
        }
    }

    public function processPayment(Order $order): array
    {
        // Create Stripe Payment Intent
        if ($this->form->payment_method == PaymentMethod::CARD->value) {
            $response = $this->paymentService->processPayment($order);
        } else {
            $response = $this->tabbyPaymentService->processPayment($order);
        }

        if (!$response["success"]) {
            $this->error = $response["error"];
            return ["success" => false];
        }
        return [
            "success" => true,
            "data" => $response,
        ];
    }
}
