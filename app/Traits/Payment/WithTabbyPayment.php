<?php

namespace App\Traits\Payment;

use App\Enums\Checkout\PaymentMethod;
use App\Services\Payment\Tabby\TabbyPaymentService;
use App\Services\Payment\Tabby\TabbyPaymentVerificationService;
use Exception;

trait WithTabbyPayment
{
    use WithTabbyData;

    public bool $isAvailable = false;
    public ?string $rejectionReason = null;
    protected TabbyPaymentService $tabbyPaymentService;
    protected TabbyPaymentVerificationService $tabbyPaymentVerificationService;

    public function initializeWithTabbyPayment(
        TabbyPaymentService $tabbyPaymentService,
        TabbyPaymentVerificationService $tabbyPaymentVerificationService
    ): void {
        if (
            session()->has("payment_method") &&
            session("payment_method") === "tabby"
        ) {
            $this->form->payment_method = PaymentMethod::CARD->value;
            if (session()->has("rejection_reason")) {
                $this->rejectionReason = session("rejection_reason");
                $this->isAvailable = true;
            }
        }
        $this->tabbyPaymentService = $tabbyPaymentService;
        $this->tabbyPaymentVerificationService = $tabbyPaymentVerificationService;
        $this->checkAvailability();
    }

    public function checkAvailability(): void
    {
        if (!$this->hasTypeCompleteAddress()) {
            $this->isAvailable = false;
            return;
        }
        try {
            $response = $this->tabbyPaymentService->checkAvailability(
                $this->getTabbyCheckoutData()
            );
            if ($response["status"] === "created") {
                $this->isAvailable = true;
                $this->rejectionReason = null;
            } else {
                $this->isAvailable = false;
                $this->rejectionReason =
                    $response["rejection_reason"] ?? "not_available";
            }
        } catch (Exception) {
            $this->isAvailable = false;
            $this->rejectionReason = "not_available";
        }
    }

    public function updatedWithTabbyPayment(): void
    {
        // Only recalculate shipping when address fields change
        if (!$this->isAvailable) {
            $this->checkAvailability();
        }
    }
}
