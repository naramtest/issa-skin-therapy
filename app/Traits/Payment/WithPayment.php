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
    }

    public function oldOrderExists(): array
    {
        $order = Order::find($this->currentOrderId);

        if ($this->form->payment_method == PaymentMethod::CARD->value) {
            try {
                $paymentData = $this->stripeOldOrderExits($order);
                logger($paymentData);
                $this->dispatch(
                    "payment-ready",
                    clientSecret: $paymentData["key"]
                );
                return ["success" => true];
            } catch (\Exception $exception) {
                logger($exception);
                return ["success" => false];
            }
        }

        return $this->tabbyPaymentService->processPayment($order);
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
