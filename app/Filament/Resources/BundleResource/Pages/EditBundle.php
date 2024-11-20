<?php

namespace App\Filament\Resources\BundleResource\Pages;

use App\Filament\Resources\BundleResource;
use App\Helpers\Filament\MediaFilamentForm;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBundle extends EditRecord
{
    protected static string $resource = BundleResource::class;

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
