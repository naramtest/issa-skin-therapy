<?php

namespace App\Traits\Payment;

use App\Enums\Checkout\PaymentMethod;
use App\Services\Payment\Tabby\TabbyPaymentService;
use App\Services\Payment\Tabby\TabbyPaymentVerificationService;
use Exception;

trait WithTabbyPayment
{
    use WithTabbyData;

    public bool $isTabbySendingRequest = false;

    public bool $isAvailable = false;
    public bool $shouldChangeRejection = true;
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
        //        $this->checkAvailability();
    }

    public function checkAvailability(): void
    {
        if ($this->isTabbySendingRequest) {
            return;
        }
        if (!$this->hasTypeCompleteAddress()) {
            $this->isAvailable = false;
            return;
        }
        try {
            $this->isTabbySendingRequest = true;
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
        } finally {
            $this->isTabbySendingRequest = true;
        }
    }
}
