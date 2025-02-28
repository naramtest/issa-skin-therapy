<?php

namespace App\Console\Commands;

use App\Services\Payment\Tabby\TabbyPaymentService;
use Illuminate\Console\Command;

class CaptureTabbyPaymentsCommand extends Command
{
    protected $signature = "tabby:capture-payments";

    protected $description = "Capture authorized Tabby payments";

    /**
     * Execute the console command.
     */
    public function handle(TabbyPaymentService $tabbyPaymentService): void
    {
        $this->info("Starting Tabby payment capture process...");
        $tabbyPaymentService->captureAuthorizedPayments();

        $this->info("Tabby payment capture process completed.");
    }
}
