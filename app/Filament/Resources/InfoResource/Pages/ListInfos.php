<?php

namespace App\Filament\Resources\InfoResource\Pages;

use App\Filament\Resources\InfoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListInfos extends ListRecords
{
    use ListRecords\Concerns\Translatable;

    protected static string $resource = InfoResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\LocaleSwitcher::make()];
    }
}
