<?php

namespace App\Console\Commands;

use App\Services\LocationService;
use Illuminate\Console\Command;

class ImportLocationsCommand extends Command
{
    protected $signature = "import:locations";

    protected $description = "Command description";

    public function handle(LocationService $locationService): int
    {
        $this->info("Starting locations import...");

        try {
            $locationService->importLocationsData();
            $this->info("Locations imported successfully!");
            return self::SUCCESS;
        } catch (\Exception $e) {
            $this->error("Failed to import locations: " . $e->getMessage());
            return self::FAILURE;
        }
    }
}
