<?php

namespace App\Traits\Checkout;

use App\Services\Payment\TabbyPaymentService;
use App\Services\Payment\TabbyPaymentVerificationService;
use Exception;
use Livewire\Attributes\On;
use Log;

trait WithPayment
{
    use WithTabbyData;

    public bool $isAvailable = false;
    public ?string $rejectionReason = null;
    protected TabbyPaymentService $tabbyPaymentService;
    protected TabbyPaymentVerificationService $tabbyPaymentVerificationService;

    public function initializeWithPayment(
        TabbyPaymentService $tabbyPaymentService,
        TabbyPaymentVerificationService $tabbyPaymentVerificationService
    ): void {
        $this->tabbyPaymentService = $tabbyPaymentService;
        $this->tabbyPaymentVerificationService = $tabbyPaymentVerificationService;
        $this->checkAvailability();
    }

    public function checkAvailability(): void
    {
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
                    $response["configuration"]["products"]["installments"][
                        "rejection_reason"
                    ] ?? "not_available";
            }
        } catch (Exception) {
            $this->isAvailable = false;
            $this->rejectionReason = "not_available";
        }
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

    #[On("payment-error")]
    public function setPaymentError($error): void
    {
        $this->error = $error;
    }

    public function processTabbyPayment($order)
    {
        try {
            $response = $this->tabbyPaymentService->processPayment($order);

            if (!$response["success"]) {
                $this->error = $response["error"];
                return;
            }
            // Redirect to Tabby checkout
            return redirect($response["url"]);
        } catch (Exception $e) {
            Log::error("Tabby payment processing failed", [
                "error" => $e->getMessage(),
            ]);
            $this->error = __(
                "store.Failed to process payment. Please try again"
            );
        }
    }
}
