<?php

namespace App\Filament\Resources\FaqSectionResource\Pages;

use App\Filament\Resources\FaqSectionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFaqSections extends ListRecords
{
    use ListRecords\Concerns\Translatable;

    protected static string $resource = FaqSectionResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\LocaleSwitcher::make(), Actions\CreateAction::make()];
    }
}
