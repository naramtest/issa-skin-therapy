<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class DeleteOldBodyAttachments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = "attachments:cleanup";

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Command description";

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $directory = storage_path("app/public/body-attachments");
        if (!File::exists($directory)) {
            $this->info("Directory does not exist.");
            return;
        }

        $files = File::allFiles($directory);
        $directories = File::directories($directory);

        $now = Carbon::now();

        foreach ($files as $file) {
            if (
                $now->diffInDays(
                    Carbon::createFromTimestamp(File::lastModified($file))
                ) > 30
            ) {
                File::delete($file);
            }
        }

        foreach ($directories as $dir) {
            if (
                $now->diffInDays(
                    Carbon::createFromTimestamp(File::lastModified($dir))
                ) > 30
            ) {
                File::deleteDirectory($dir);
            }
        }

        $this->info("Old body attachments cleaned up successfully.");
    }
}
