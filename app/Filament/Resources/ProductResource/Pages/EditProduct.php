<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
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
        $image = $this->record->getFirstMedia(config("const.media.featured"));
        $data["featured_caption"] = $image->custom_properties["caption"] ?? "";
        $data["featured_title"] = $image->custom_properties["title"] ?? "";
        $data["featured_alt"] = $image->custom_properties["alt"] ?? "";

        return parent::mutateFormDataBeforeFill($data);
    }
}
