<?php

namespace App\Traits\Payment;

use App\Services\Payment\Tabby\TabbyPaymentService;
use App\Services\Payment\Tabby\TabbyPaymentVerificationService;
use Exception;

trait TabbyPayment
{
    use WithTabbyData;

    public bool $isAvailable = false;
    public ?string $rejectionReason = null;
    protected TabbyPaymentService $tabbyPaymentService;
    protected TabbyPaymentVerificationService $tabbyPaymentVerificationService;

    public function initializeTabbyPayment(
        TabbyPaymentService $tabbyPaymentService,
        TabbyPaymentVerificationService $tabbyPaymentVerificationService
    ): void {
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

    public function updatedTabbyPayment(): void
    {
        // Only recalculate shipping when address fields change

        if (!$this->isAvailable) {
            $this->checkAvailability();
        }
    }
}
