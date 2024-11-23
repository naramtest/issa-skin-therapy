<?php

namespace App\Helpers\Filament\Post\Components;

use App\Enums\ProductStatus;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieTagsInput;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Get;

class SeoTab
{
    public static function make()
    {
        return Tab::make("SEO")
            ->icon("gmdi-info-o")
            ->schema([
                TextInput::make("meta_title")
                    ->label("Meta Title")
                    ->maxLength(60)
                    ->counter("meta_title", 60)
                    ->required(function (Get $get) {
                        return $get("status") != ProductStatus::DRAFT->value;
                    }),
                Textarea::make("meta_description")
                    ->label("Meta Description")
                    ->maxLength(160)
                    ->counter("meta_description", 160),
                Select::make("user_id")
                    ->label(__("dashboard.Author"))
                    ->relationship("author", "name")
                    ->default(auth()->user()->id)
                    ->preload(),
                SpatieTagsInput::make("tags")->label(__("dashboard.Tags")),
            ]);
    }
}
