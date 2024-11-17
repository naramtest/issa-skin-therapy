<?php

namespace App\Filament\Resources\ProductCategoryResource\Pages;

use App\Filament\Resources\ProductCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageProductCategories extends ManageRecords
{
    use ManageRecords\Concerns\Translatable;

    protected static string $resource = ProductCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\CreateAction::make(), Actions\LocaleSwitcher::make()];
    }
}
