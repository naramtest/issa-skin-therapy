<?php

namespace App\Services\Payment\Tabby;

use Exception;
use Http;
use Log;

class TabbyWebhookRegistrationService
{
    private string $baseUrl;
    private string $secretKey;
    private string $merchantCode;
    private bool $isTestMode;

    public function __construct()
    {
        $this->baseUrl = "https://api.tabby.ai/api/v1/";
        $this->secretKey = config("services.tabby.secret_key");
        $this->merchantCode = config("services.tabby.merchant_code");
        $this->isTestMode = !app()->isProduction();
    }

    public function registerWebhook(
        string $webhookUrl,
        ?string $customAuthHeader = null
    ): array {
        try {
            logger($webhookUrl);
            $response = Http::withHeaders([
                "Authorization" => "Bearer " . $this->secretKey,
                "X-Merchant-Code" => $this->merchantCode,
                "Content-Type" => "application/json",
            ])->post("https://api.tabby.ai/api/v1/webhooks", [
                "url" => $webhookUrl,
                "is_test" => $this->isTestMode,
            ]);

            if (!$response->successful()) {
                Log::error("Failed to register Tabby webhook", [
                    "status" => $response->status(),
                    "body" => $response->json(),
                    "merchant_code" => $this->merchantCode,
                ]);

                return [
                    "success" => false,
                    "message" =>
                        "Failed to register webhook: " .
                        ($response->json()["message"] ?? "Unknown error"),
                    "status" => $response->status(),
                ];
            }

            return [
                "success" => true,
                "data" => $response->json(),
                "message" => "Webhook registered successfully",
            ];
        } catch (Exception $e) {
            return [
                "success" => false,
                "message" => "Failed to register webhook: " . $e->getMessage(),
            ];
        }
    }
}
