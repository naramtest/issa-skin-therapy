<?php

namespace App\Traits\Payment;

use App\Models\Order;
use App\Services\Payment\StripePaymentService;
use Exception;

trait WithStripePayment
{
    protected StripePaymentService $paymentService;

    public function initializeWithStripePayment(
        StripePaymentService $paymentService
    ): void {
        $this->paymentService = $paymentService;
    }

    public function getBillingDetails(): array
    {
        return [
            "name" =>
                $this->form->billing_first_name .
                " " .
                $this->form->billing_last_name,
            "email" => $this->form->email,
            "phone" => $this->form->phone,
            "address" => [
                "line1" => $this->form->billing_address,
                "line2" =>
                    $this->form->billing_building .
                    " " .
                    $this->form->billing_flat,
                "city" => $this->form->billing_city,
                "state" => $this->form->billing_state,
                "postal_code" => $this->form->billing_postal_code,
                "country" => $this->form->billing_country,
            ],
        ];
    }

    /**
     * @throws Exception
     */
    public function stripeOldOrderExits(Order $order): ?array
    {
        if (!$this->orderService->isOrderPendingPayment($order)) {
            throw new Exception("no Pending Payment");
        }

        return $this->paymentService->getPaymentIntent(
            $order->payment_intent_id
        );
    }
}
