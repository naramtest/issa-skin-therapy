<?php

namespace App\Console\Commands;

use App\Services\Export\FTPServerService;
use Illuminate\Console\Command;

class ProcessDHLTracking extends Command
{
    protected $signature = "dhl:process-tracking";
    protected $description = "Process tracking files from DHL Commerce";

    /**
     * @throws \Exception
     */
    public function handle(FTPServerService $ftpService): void
    {
        $ftpService->processTrackingUpdates();
        $this->info("DHL tracking updates processed");
    }
}
