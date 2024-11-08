<?php

namespace App\Services\Filament\Component;

use App\Enums\CategoryType;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Illuminate\Database\Eloquent\Builder;

class CategoryFilament
{
    public static function Select(CategoryType $type, bool $isInline = true)
    {
        return Select::make("categories")
            ->multiple()
            ->createOptionForm(self::makeOptionForm($type))
            ->inlineLabel($isInline)
            ->required()
            ->label(__("dashboard.Categories"))
            ->relationship(
                "categories",
                "name",
                fn(Builder $query) => $query->where("type", $type)
            )
            ->preload();
    }

    public static function makeOptionForm(CategoryType $type): array
    {
        return [
            Toggle::make("is_visible")->default(true)->columnSpan(2),
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
        ];
    }
}
