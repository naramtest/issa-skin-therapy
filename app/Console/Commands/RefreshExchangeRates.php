<?php

namespace App\Console\Commands;

use App\Services\Currency\Currency;
use Illuminate\Console\Command;

class RefreshExchangeRates extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = "currency:refresh-rates";

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Command description";

    public function handle(): void
    {
        $this->info("Refreshing exchange rates...");
        Currency::cacheExchangeRates();
        $this->info("Exchange rates have been refreshed!");
    }
}
