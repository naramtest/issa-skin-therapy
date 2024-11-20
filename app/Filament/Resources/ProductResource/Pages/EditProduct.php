<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use App\Helpers\Filament\MediaFilamentForm;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProduct extends EditRecord
{
    protected static string $resource = ProductResource::class;

    use EditRecord\Concerns\Translatable;

    protected function getHeaderActions(): array
    {
        return [Actions\LocaleSwitcher::make(), Actions\DeleteAction::make()];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data = MediaFilamentForm::fill(
            config("const.media.featured"),
            $data,
            $this->record
        );

        return parent::mutateFormDataBeforeFill($data);
    }

    protected function afterSave(): void
    {
        MediaFilamentForm::save(
            config("const.media.featured"),
            $this->data,
            $this->record
        );
    }
}
