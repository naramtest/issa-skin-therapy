<?php

namespace App\Console\Commands;

use App\Exports\FacebookCatalogExport;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class ExportFacebookCatalogCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = "catalog:export-facebook";

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Export product catalog for Facebook Marketing API";

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        // Fixed filename
        $filename = "facebook_catalog.xlsx";
        $dir = "feeds";
        $storage = Storage::disk("public");
        if (!$storage->exists($dir)) {
            $storage->makeDirectory($dir);
        }
        // Export the catalog
        Excel::store(
            new FacebookCatalogExport(),
            $dir . "/" . $filename,
            "public"
        );

        // Generate public URL
        $publicUrl = url("storage/" . $dir . "/" . $filename);

        $this->info("Facebook Catalog exported successfully!");
        $this->info("Filename: {$filename}");
        $this->info("Public URL: {$publicUrl}");
    }
}
