<?php

namespace App\Console\Commands;

use App\Models\Info;
use Illuminate\Console\Command;

class InfoCreateCommand extends Command
{
    protected $signature = 'info:create';

    protected $description = 'Command description';

    public function handle(): void
    {
        if (Info::count() > 0) {
            $this->info("Info already exists!");
            return;
        }
        $info = Info::create([
                'name' => [
                    'en' => 'Company Name',
                ],
                'about' => [
                    'en' => 'About company',

                ],
                'address' => [
                    'en' => 'Address',

                ],
                'slogan' => [
                    'en' => 'Slogan',

                ],]
        );
        $this->info("Info has been created!" . $info->id);

    }
}
