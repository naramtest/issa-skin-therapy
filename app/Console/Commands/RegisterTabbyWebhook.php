<?php

namespace App\Console\Commands;

use App\Services\Payment\Tabby\TabbyWebhookRegistrationService;
use Illuminate\Console\Command;
use Symfony\Component\Console\Command\Command as CommandAlias;

class RegisterTabbyWebhook extends Command
{
    protected $signature = 'tabby:register-webhook
                          {--url= : The webhook URL to register}
                          {--auth-header= : Optional custom authorization header}';

    protected $description = "Register a webhook URL with Tabby payment service";

    private TabbyWebhookRegistrationService $webhookService;

    public function __construct(TabbyWebhookRegistrationService $webhookService)
    {
        parent::__construct();
        $this->webhookService = $webhookService;
    }

    public function handle()
    {
        $webhookUrl = $this->option("url") ?? route("webhooks.tabby");
        $authHeader = $this->option("auth-header");

        $this->info("Registering Tabby webhook...");
        $this->info("URL: {$webhookUrl}");

        if ($authHeader) {
            $this->info("Using custom authorization header");
        }

        $result = $this->webhookService->registerWebhook(
            $webhookUrl,
            $authHeader
        );

        if ($result["success"]) {
            $this->info("✓ Webhook registered successfully!");
            $this->info("Webhook Details:");
            $this->table(
                ["Key", "Value"],
                collect($result["data"] ?? [])->map(
                    fn($value, $key) => [
                        $key,
                        is_string($value) ? $value : json_encode($value),
                    ]
                )
            );
        } else {
            $this->error("✗ Failed to register webhook");
            $this->error($result["message"]);
        }

        return $result["success"]
            ? CommandAlias::SUCCESS
            : CommandAlias::FAILURE;
    }
}
