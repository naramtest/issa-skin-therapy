<?php

namespace App\Filament\Resources\FaqSectionResource\Pages;

use App\Filament\Resources\FaqSectionResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateFaqSection extends CreateRecord
{
    use CreateRecord\Concerns\Translatable;

    protected static string $resource = FaqSectionResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\LocaleSwitcher::make()];
    }
}
