<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PostResource\Pages;
use App\Helpers\Filament\Post\PostForm;
use App\Helpers\Filament\Post\PostTable;
use App\Models\Post;
use Exception;
use Filament\Forms\Form;
use Filament\Resources\Concerns\Translatable;
use Filament\Resources\Resource;
use Filament\Tables\Table;

class PostResource extends Resource
{
    use Translatable;

    protected static ?string $model = Post::class;

    protected static ?string $navigationIcon = "gmdi-rss-feed-o";

    protected static ?int $navigationSort = 1;

    /**
     * @throws Exception
     */
    public static function form(Form $form): Form
    {
        return PostForm::form($form);
    }

    /**
     * @throws Exception
     */
    public static function table(Table $table): Table
    {
        return PostTable::table($table);
    }

    public static function getPages(): array
    {
        return [
            "index" => Pages\ListPosts::route("/"),
            "create" => Pages\CreatePost::route("/create"),
            "edit" => Pages\EditPost::route("/{record}/edit"),
        ];
    }

    public static function getLabel(): ?string
    {
        return __("dashboard.Post");
    }

    public static function getModelLabel(): string
    {
        return __("dashboard.Post");
    }

    public static function getNavigationLabel(): string
    {
        return __("dashboard.Posts");
    }

    public static function getPluralLabel(): ?string
    {
        return __("dashboard.Posts");
    }

    public static function getPluralModelLabel(): string
    {
        return __("dashboard.Posts");
    }

    public static function getNavigationGroup(): ?string
    {
        return __("dashboard.Content");
    }
}
