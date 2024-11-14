<?php

namespace App\Filament\Resources\BundleResource\Pages;

use App\Filament\Resources\BundleResource;
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
        $image = $this->record->getFirstMedia(config("const.media.featured"));
        $data["featured_caption"] = $image->custom_properties["caption"] ?? "";
        $data["featured_title"] = $image->custom_properties["title"] ?? "";
        $data["featured_alt"] = $image->custom_properties["alt"] ?? "";

        return parent::mutateFormDataBeforeFill($data);
    }
}
