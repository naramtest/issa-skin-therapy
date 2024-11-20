<?php

namespace App\Helpers\Filament\Purchasable;

use App\Services\Filament\Component\CustomNameSlugField;
use Filament\Forms;
use Filament\Forms\Components\Tabs;

class BasicInformation
{
    public static function make(array $array)
    {
        return Tabs\Tab::make(__("dashboard.Basic Information"))
            ->icon("gmdi-inventory-2-o")
            ->schema([
                CustomNameSlugField::getCustomTitleField(
                    label: __("store.Name"),
                    fieldName: "name"
                )
                    ->translate(true)
                    ->inlineLabel(),
                CustomNameSlugField::getCustomSlugField()
                    ->helperText(
                        "https://" . request()->getHost() . "/product/"
                    )
                    ->inlineLabel()
                    ->label(__("dashboard.Permalink")),

                Forms\Components\TextInput::make("sku")
                    ->label("SKU")
                    ->unique(ignoreRecord: true)
                    ->inlineLabel()
                    ->placeholder(
                        __(
                            "dashboard.Will be generated automatically if left empty"
                        )
                    ),

                Forms\Components\RichEditor::make("description")
                    ->required()
                    ->label(__("dashboard.Description"))
                    ->columnSpanFull(),
                ...$array,
            ]);
    }
}
