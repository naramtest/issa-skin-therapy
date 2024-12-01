<?php

namespace App\Filament\Resources\InfoResource\Pages;

use App\Filament\Resources\InfoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditInfo extends EditRecord
{
    use EditRecord\Concerns\Translatable;

    protected static string $resource = InfoResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\DeleteAction::make(), Actions\LocaleSwitcher::make()];
    }
}
