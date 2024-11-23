<?php

namespace App\Filament\Resources\PostResource\Pages;

use App\Enums\ProductStatus;
use App\Filament\Resources\PostResource;
use App\Helpers\Filament\MediaFilamentForm;
use App\Helpers\Posts\AutoSaveActions;
use App\Helpers\Posts\PostRedis;
use App\Services\Post\AutoEditPost;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\LocaleSwitcher;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord\Concerns\Translatable;

class EditPost extends AutoEditPost
{
    use Translatable;

    protected static string $resource = PostResource::class;

    public function saveBeforePreview(): void
    {
        PostRedis::save($this->record, $this->data);
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $mediaTypes = [
            config("const.media.featured"),
            config("const.media.post.cover"),
        ];

        foreach ($mediaTypes as $mediaType) {
            $data = MediaFilamentForm::fill($mediaType, $data, $this->record);
        }

        return parent::mutateFormDataBeforeFill($data);
    }

    protected function afterSave(): void
    {
        $mediaTypes = [
            config("const.media.featured"),
            config("const.media.post.body"),
        ];

        foreach ($mediaTypes as $mediaType) {
            MediaFilamentForm::save($mediaType, $this->data, $this->record);
        }
    }

    protected function getHeaderActions(): array
    {
        return $this->getActions();
    }

    public function getActions(): array
    {
        $actions = [
            LocaleSwitcher::make(),
            AutoSaveActions::save()->action(function () {
                $this->save();
            }),
            DeleteAction::make(),
            RestoreAction::make(),
            ForceDeleteAction::make(),
        ];
        if ($this->record->status != ProductStatus::PUBLISHED) {
            $actions[] = AutoSaveActions::publish()->action(function () {
                $this->data["status"] = ProductStatus::PUBLISHED->value;
                $this->save();
            });
        } else {
            $actions[] = AutoSaveActions::show();
        }

        return $actions;
    }

    protected function getFormActions(): array
    {
        return $this->getActions();
    }
}
