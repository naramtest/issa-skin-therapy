<?php

namespace App\Helpers\Filament\Category;

use App\Enums\CategoryType;
use App\Helpers\Filament\Component\CustomNameSlugField;
use Filament\Forms\Components\Hidden;
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
            CustomNameSlugField::getCustomSlugField(),
            Textarea::make("description")
                ->label(__("dashboard.Description"))
                ->rows(3)
                ->maxLength(160)
                ->columnSpan(2),
            Hidden::make("type")->default($type),
        ]);
    }
}
