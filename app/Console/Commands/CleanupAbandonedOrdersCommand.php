<?php

namespace App\Console\Commands;

use App\Jobs\CleanupAbandonedOrdersJob;
use Illuminate\Console\Command;

class CleanupAbandonedOrdersCommand extends Command
{
    protected $signature = "orders:cleanup-abandoned";

    protected $description = "Clean up abandoned orders and release held inventory";

    public function handle(): void
    {
        $this->info("Dispatching cleanup job for abandoned orders...");
        CleanupAbandonedOrdersJob::dispatch();
        $this->info("Job dispatched successfully!");
    }
}
