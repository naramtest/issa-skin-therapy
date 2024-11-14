<?php

namespace App\Filament\Resources;

use App\Enums\ProductStatus;
use App\Enums\StockStatus;
use App\Filament\Resources\BundleResource\Pages;
use App\Models\Bundle;
use App\Models\Product;
use App\Services\Filament\Component\CustomNameSlugField;
use App\Services\Filament\Component\FullImageSectionUpload;
use Carbon\Carbon;
use Exception;
use Filament\Forms;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Form;
use Filament\Resources\Concerns\Translatable;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Pelmered\FilamentMoneyField\Forms\Components\MoneyInput;

class BundleResource extends Resource
{
    use Translatable;

    protected static ?string $model = Bundle::class;
    protected static ?string $navigationIcon = "heroicon-o-cube";
    protected static ?string $navigationGroup = "Shop";
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form->columns(3)->schema([
            Forms\Components\Group::make()
                ->schema([
                    Tabs::make("Bundle")->tabs([
                        Tabs\Tab::make(
                            __("dashboard.Basic Information")
                        )->schema([
                            CustomNameSlugField::getCustomTitleField(
                                label: __("store.Name"),
                                fieldName: "name"
                            )
                                ->translate(true)
                                ->inlineLabel(),

                            CustomNameSlugField::getCustomSlugField()
                                ->helperText(
                                    "https://" .
                                        request()->getHost() .
                                        "/bundle/"
                                )
                                ->inlineLabel()
                                ->label(__("dashboard.Permalink")),

                            Forms\Components\TextInput::make("sku")
                                ->label("SKU")
                                ->unique(ignoreRecord: true)
                                ->inlineLabel()
                                ->placeholder(
                                    "Will be generated automatically if left empty"
                                ),

                            Forms\Components\RichEditor::make("description")
                                ->required()
                                ->label(__("dashboard.Description"))
                                ->columnSpanFull(),
                        ]),

                        // Bundle Items Tab
                        Tabs\Tab::make(__("dashboard.Bundle Items"))->schema([
                            Repeater::make("items")
                                ->hiddenLabel()
                                ->relationship("items")
                                ->schema([
                                    Select::make("product_id")
                                        ->label(__("dashboard.Product"))
                                        ->relationship("product", "name")
                                        ->required()
                                        ->searchable()
                                        ->preload()
                                        ->columnSpan(2),

                                    TextInput::make("quantity")
                                        ->label(__("dashboard.Quantity"))
                                        ->numeric()
                                        ->default(1)
                                        ->required()
                                        ->minValue(1)
                                        ->columnSpan(1),
                                ])
                                ->columnSpanFull()
                                ->columns(3)
                                ->itemLabel(
                                    fn(array $state): ?string => $state[
                                        "product_id"
                                    ]
                                        ? Product::find($state["product_id"])
                                                ?->name .
                                            " (Qty: " .
                                            ($state["quantity"] ?? 1) .
                                            ")"
                                        : null
                                ),
                        ]),
                        Tabs\Tab::make(__("store.Shipping"))
                            ->icon("gmdi-shopping-cart-o")
                            ->columns()
                            ->schema([
                                Forms\Components\TextInput::make("weight")
                                    ->label(__("dashboard.Weight"))
                                    ->numeric()
                                    ->step(0.001)
                                    ->suffix("kg"),

                                Forms\Components\TextInput::make("length")
                                    ->label(__("dashboard.Length"))
                                    ->numeric()
                                    ->step(0.01)
                                    ->suffix("cm"),

                                Forms\Components\TextInput::make("width")
                                    ->label(__("dashboard.Width"))
                                    ->numeric()
                                    ->step(0.01)
                                    ->suffix("cm"),

                                Forms\Components\TextInput::make("height")
                                    ->label(__("dashboard.Height"))
                                    ->numeric()
                                    ->step(0.01)
                                    ->suffix("cm"),
                            ]),

                        Tabs\Tab::make(__("dashboard.Media"))
                            ->icon("gmdi-image-o")
                            ->columns()
                            ->schema([
                                Fieldset::make(
                                    __("dashboard.Featured")
                                )->schema(
                                    FullImageSectionUpload::make(
                                        config("const.media.featured"),
                                        __("dashboard.Featured"),
                                        config("const.media.featured")
                                    )
                                ),

                                Fieldset::make("Gallery")->schema([
                                    SpatieMediaLibraryFileUpload::make(
                                        config("const.media.gallery")
                                    )
                                        ->hiddenLabel()
                                        ->collection(
                                            config("const.media.gallery")
                                        )
                                        ->columnSpan(1)
                                        ->imageEditor()
                                        ->image()
                                        ->multiple()
                                        ->live()
                                        ->downloadable()
                                        ->maxSize(5120)
                                        ->imageEditorAspectRatios([
                                            null,
                                            "16:9",
                                            "4:3",
                                            "1:1",
                                        ]),
                                ]),
                            ]),
                    ]),
                ])
                ->columnSpan(2),

            Forms\Components\Group::make()
                ->schema([
                    // Status Section
                    Section::make("Status")->schema([
                        ToggleButtons::make("status")
                            ->options(ProductStatus::class)
                            ->inline()
                            ->default(ProductStatus::PUBLISHED)
                            ->extraInputAttributes([
                                "class" => "toggle-button",
                            ])
                            ->live()
                            ->hiddenLabel()
                            ->grouped(),
                        DateTimePicker::make("published_at")
                            ->maxDate(now()->addDay())
                            ->label(__("dashboard.Published At"))
                            ->live()
                            ->default(function ($operation) {
                                if ($operation == "create") {
                                    return now();
                                }

                                return null;
                            })
                            ->minDate(fn() => Carbon::today())
                            ->visible(
                                fn(callable $get) => $get("status") ===
                                    ProductStatus::PUBLISHED
                            )
                            ->displayFormat("d-m-Y-H-i-s"),
                    ]),

                    Section::make(__("dashboard.Pricing"))->schema([
                        Toggle::make("auto_calculate_price")
                            ->label(__("dashboard.Auto Calculate Price"))
                            ->default(true)
                            ->reactive(),

                        MoneyInput::make("regular_price")
                            ->label(__("dashboard.Regular Price"))
                            ->required()
                            ->disabled(
                                fn(callable $get) => $get(
                                    "auto_calculate_price"
                                )
                            ),

                        MoneyInput::make("sale_price")
                            ->label(__("dashboard.Sale Price"))
                            ->nullable()
                            ->disabled(
                                fn(callable $get) => $get(
                                    "auto_calculate_price"
                                )
                            ),

                        Toggle::make("is_sale_scheduled")
                            ->label(__("dashboard.Schedule Sale"))
                            ->reactive(),

                        DateTimePicker::make("sale_starts_at")
                            ->label(__("dashboard.Sale Start Date"))
                            ->visible(
                                fn(callable $get) => $get("is_sale_scheduled")
                            ),

                        DateTimePicker::make("sale_ends_at")
                            ->label(__("dashboard.Sale End Date"))
                            ->visible(
                                fn(callable $get) => $get("is_sale_scheduled")
                            )
                            ->after("sale_starts_at"),
                    ]),

                    // Inventory Section
                    Section::make(__("dashboard.Inventory"))->schema([
                        Toggle::make("bundle_level_stock")
                            ->label(__("dashboard.Enable Bundle Level Stock"))
                            ->helperText(
                                __(
                                    'dashboard.Manage stock at bundle level instead of using product stocks"'
                                )
                            )
                            ->default(false)
                            ->reactive(),

                        Toggle::make("track_quantity")
                            ->label(__("dashboard.Track Quantity"))
                            ->default(true)
                            ->reactive()
                            ->visible(
                                fn(callable $get) => $get("bundle_level_stock")
                            ),

                        TextInput::make("quantity")
                            ->numeric()
                            ->default(0)
                            ->visible(
                                fn(callable $get) => $get(
                                    "bundle_level_stock"
                                ) && $get("track_quantity")
                            ),

                        TextInput::make("low_stock_threshold")
                            ->label(__("dashboard.Low Stock"))
                            ->numeric()
                            ->default(5)
                            ->visible(
                                fn(callable $get) => $get(
                                    "bundle_level_stock"
                                ) && $get("track_quantity")
                            ),

                        Toggle::make("allow_backorders")
                            ->label("Allow Backorders")
                            ->default(false)
                            ->visible(
                                fn(callable $get) => $get(
                                    "bundle_level_stock"
                                ) && $get("track_quantity")
                            ),

                        Select::make("stock_status")
                            ->options(StockStatus::class)
                            ->default(StockStatus::IN_STOCK)
                            ->visible(
                                fn(callable $get) => $get(
                                    "bundle_level_stock"
                                ) && !$get("track_quantity")
                            ),
                    ]),
                ])
                ->columnSpan(1),
        ]);
    }

    /**
     * @throws Exception
     */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make("name")
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make("regular_price")
                    ->money()
                    ->sortable(),

                Tables\Columns\TextColumn::make("stock_status")->badge()->color(
                    fn(StockStatus $state): string => match ($state) {
                        StockStatus::IN_STOCK => "success",
                        StockStatus::LOW_STOCK => "warning",
                        StockStatus::OUT_OF_STOCK => "danger",
                        StockStatus::BACKORDER => "info",
                        default => "gray",
                    }
                ),

                Tables\Columns\TextColumn::make("updated_at")
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make("stock_status")->options(
                    StockStatus::class
                ),
                Tables\Filters\TernaryFilter::make("bundle_level_stock"),
                Tables\Filters\TernaryFilter::make("is_sale_scheduled")->label(
                    "On Sale"
                ),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            "index" => Pages\ListBundles::route("/"),
            "create" => Pages\CreateBundle::route("/create"),
            "edit" => Pages\EditBundle::route("/{record}/edit"),
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ["name", "sku"];
    }

    public static function getModelLabel(): string
    {
        return __("store.Bundle");
    }

    public static function getPluralModelLabel(): string
    {
        return __("store.Bundles");
    }
}
