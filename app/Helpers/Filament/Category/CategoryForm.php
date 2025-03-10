<?php

namespace App\Helpers\Filament\Category;

use App\Enums\CategoryType;
use App\Helpers\Filament\Component\CustomNameSlugField;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;

class CategoryForm
{
    public static function make(Form $form, CategoryType $type): Form
    {
        return $form->schema([
            Toggle::make("is_visible")
                ->label(__("dashboard.Visible"))
                ->default(true)
                ->columnSpan(2),
            CustomNameSlugField::getCustomTitleField(
                label: __("store.Name"),
                fieldName: "name"
            ),
            CustomNameSlugField::getCustomSlugField(
                unique: $type !== CategoryType::PRODUCT
            ),
            Textarea::make("description")
                ->label(__("dashboard.Description"))
                ->rows(3)
                ->maxLength(160)
                ->columnSpan(2),
            Hidden::make("type")->default($type),
            SpatieMediaLibraryFileUpload::make(config("const.media.featured"))
                ->label(__("dashboard.Featured"))
                ->collection(config("const.media.featured"))
                ->hiddenLabel()
                ->columnSpan(1)
                ->imageEditor()
                ->image()
                ->live()
                ->downloadable()
                ->maxSize(5120)
                ->imageEditorAspectRatios([null, "16:9", "4:3", "1:1"]),
        ]);
    }
}
