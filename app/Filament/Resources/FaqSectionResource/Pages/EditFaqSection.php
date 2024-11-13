<?php

namespace App\Filament\Resources\FaqSectionResource\Pages;

use App\Filament\Resources\FaqSectionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFaqSection extends EditRecord
{
    use EditRecord\Concerns\Translatable;

    protected static string $resource = FaqSectionResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\LocaleSwitcher::make(), Actions\DeleteAction::make()];
    }
}
