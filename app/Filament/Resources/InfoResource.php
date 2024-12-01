<?php

namespace App\Filament\Resources;

use App\Enums\SocialNetwork;
use App\Filament\Resources\InfoResource\Pages;
use App\Filament\Resources\InfoResource\RelationManagers;
use App\Models\Info;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Concerns\Translatable;
use Filament\Resources\Resource;
use Filament\Tables\Table;

class InfoResource extends Resource
{
    use Translatable;

    protected static ?string $model = Info::class;

    protected static ?string $navigationIcon = "gmdi-info-o";
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Tabs::make(__("dashboard.info.title"))
                ->schema([
                    Tab::make("Info")
                        ->label(__("dashboard.info.title"))
                        ->icon("gmdi-info-o")
                        ->schema([
                            TextInput::make("name")
                                ->label(__("store.Name"))
                                ->placeholder(
                                    __("dashboard.info.name_placeholder")
                                )
                                ->required()
                                ->maxLength(50)
                                ->translate()
                                ->columnSpan(1),
                            TextInput::make("slogan")
                                ->label(__("dashboard.info.slogan"))
                                ->translate()
                                ->columnSpan(1),
                            Textarea::make("about")
                                ->label(__("dashboard.info.about"))
                                ->maxLength(400)
                                ->translate()
                                ->columnSpan(1)
                                ->rows(3),

                            Textarea::make("address")
                                ->label(__("dashboard.info.address"))
                                ->columnSpan(1)
                                ->maxLength(250)
                                ->translate()
                                ->rows(3),
                        ])
                        ->columns(),
                    Tab::make("Contact")
                        ->label(__("dashboard.info.contacts"))
                        ->icon("gmdi-contacts-o")
                        ->schema([
                            Repeater::make("phone")
                                ->schema([
                                    TextInput::make("number")
                                        ->maxLength(100)
                                        ->tel()
                                        ->telRegex(
                                            '/^[+]*[(]{0,1}[0-9]{1,4}[)]{0,1}[-\s\.\/0-9]*$/'
                                        )
                                        ->prefixIcon("gmdi-call-o")
                                        ->hiddenLabel(),
                                ])
                                ->columnSpan(1)
                                ->label(__("dashboard.info.phone")),

                            Repeater::make("email")
                                ->schema([
                                    TextInput::make("email")
                                        ->email()
                                        ->hiddenLabel()
                                        ->maxLength(100)
                                        ->prefixIcon("gmdi-email-o"),
                                ])
                                ->columnSpan(1)
                                ->label(__("dashboard.email")),
                        ])
                        ->columns(),
                    Tab::make("Social Media")
                        ->label(__("dashboard.info.social_title"))
                        ->icon("gmdi-tag-o")
                        ->schema([
                            Repeater::make("social")
                                ->schema([
                                    Select::make("name")
                                        ->options(SocialNetwork::class)
                                        ->live(onBlur: true)
                                        ->hiddenLabel()
                                        ->selectablePlaceholder(false)
                                        ->prefixIcon("gmdi-share-o")
                                        ->columnSpan(1),
                                    TextInput::make("url")
                                        ->url()
                                        ->hiddenLabel()
                                        ->maxLength(100)
                                        ->prefixIcon("gmdi-link-o")
                                        ->columnSpan(2),
                                ])
                                ->columns(3)
                                ->defaultItems(1)
                                ->label(trans("dashboard.info.social_title")),
                        ]),
                ])
                ->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table;
    }

    public static function getPages(): array
    {
        return [
            "index" => Pages\ListInfos::route("/"),
            "edit" => Pages\EditInfo::route("/{record}/edit"),
        ];
    }

    public static function getNavigationUrl(): string
    {
        return InfoResource::getUrl("edit", ["record" => "1"]);
    }

    public static function getLabel(): ?string
    {
        return __("dashboard.info.label");
    }

    public static function getModelLabel(): string
    {
        return __("dashboard.info.label");
    }

    public static function getNavigationLabel(): string
    {
        return __("dashboard.info.label");
    }

    public static function getPluralLabel(): ?string
    {
        return __("dashboard.info.label");
    }

    public static function getPluralModelLabel(): string
    {
        return __("dashboard.info.label");
    }

    public static function getNavigationGroup(): ?string
    {
        return __('dashboard.Settings');
    }
}
