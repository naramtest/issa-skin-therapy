<?php

namespace App\Filament\Resources\BundleResource\Pages;

use App\Filament\Resources\BundleResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateBundle extends CreateRecord
{
    use CreateRecord\Concerns\Translatable;

    protected static string $resource = BundleResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\LocaleSwitcher::make()];
    }
}
