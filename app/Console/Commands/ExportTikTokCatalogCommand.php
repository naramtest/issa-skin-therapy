<?php

namespace App\Console\Commands;

use App\Exports\TikTokCatalogExport;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class ExportTikTokCatalogCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = "catalog:export-tiktok";

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Export product catalog for TikTok Marketing API";

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        // Fixed filename
        $filename = "tiktok_catalog.csv";
        $dir = "feeds";
        $storage = Storage::disk("public");

        if (!$storage->exists($dir)) {
            $storage->makeDirectory($dir);
        }

        // Export the catalog
        Excel::store(
            new TikTokCatalogExport(),
            $dir . "/" . $filename,
            "public",
            \Maatwebsite\Excel\Excel::CSV
        );

        // Generate public URL
        $publicUrl = url("storage/" . $dir . "/" . $filename);

        $this->info("TikTok Catalog exported successfully!");
        $this->info("Filename: {$filename}");
        $this->info("Public URL: {$publicUrl}");
    }
}
