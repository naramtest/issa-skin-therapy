<?php

namespace App\Helpers\Filament\Post\Components;

use App\Enums\ProductStatus;
use App\Helpers\Filament\Component\CustomNameSlugField;
use App\Helpers\Filament\Component\CustomTinyEditor;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Get;

class ContentTab
{
    public static function make()
    {
        return Tab::make(__("dashboard.Content"))
            ->icon("gmdi-source-o")
            ->schema([
                CustomNameSlugField::getCustomTitleField(
                    label: __("dashboard.Title")
                )->inlineLabel(),
                CustomNameSlugField::getCustomSlugField()
                    ->inlineLabel()
                    ->prefix("https://" . request()->getHost() . "/post/")
                    ->label(__("dashboard.Permalink")),
                Textarea::make("excerpt")
                    ->label(__("dashboard.Description"))
                    ->rows(3)
                    ->maxLength(250)
                    ->counter("excerpt", 250),
                CustomTinyEditor::TinyEditor(
                    "body",
                    __("dashboard.Content")
                )->required(function (Get $get) {
                    return $get("status") != ProductStatus::DRAFT->value;
                }),
            ]);
    }
}
