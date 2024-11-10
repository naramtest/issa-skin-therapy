<?php

namespace App\Filament\Resources;

use App\Enums\CategoryType;
use App\Enums\ProductStatus;
use App\Enums\StockStatus;
use App\Filament\Resources\ProductResource\Pages;
use App\Models\Product;
use App\Services\Filament\Component\CategoryFilament;
use App\Services\Filament\Component\CustomNameSlugField;
use App\Services\Filament\Component\FullImageSectionUpload;
use Carbon\Carbon;
use Exception;
use Filament\Forms;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\SpatieTagsInput;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Form;
use Filament\Resources\Concerns\Translatable;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Pelmered\FilamentMoneyField\Forms\Components\MoneyInput;

class ProductResource extends Resource
{
    //    TODO: reviews
    //    TODO: add Preview Button
    //    TODO: refactor form and table

    use Translatable;

    protected static ?string $model = Product::class;
    protected static ?string $navigationIcon = "heroicon-o-shopping-bag";
    protected static ?string $navigationGroup = "Shop";
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form->columns(3)->schema([
            Forms\Components\Group::make()
                ->schema([
                    Tabs::make("Product")->tabs([
                        // Basic Information Tab
                        Tabs\Tab::make(__("dashboard.Basic Information"))
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
                                        "https://" .
                                            request()->getHost() .
                                            "/product/"
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
                                Forms\Components\RichEditor::make(
                                    "short_description"
                                )
                                    ->label(__("dashboard.Short Description"))
                                    ->required()
                                    ->columnSpanFull(),
                            ]),

                        // Shipping Tab
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

                                Forms\Components\TextInput::make(
                                    "hs_code"
                                )->label("HS Code"),

                                Forms\Components\Select::make(
                                    "country_of_origin"
                                )
                                    ->searchable()
                                    ->label(__("dashboard.Country Of Origin"))
                                    ->options(function () {
                                        // TODO:You'll need to implement this with a proper country list
                                        return [
                                            "AE" => "United Arab Emirates",
                                            "US" => "United States",
                                            // Add more countries
                                        ];
                                    }),
                            ]),

                        // Additional Information Tab
                        Tabs\Tab::make(__("dashboard.Additional Information"))
                            ->icon("gmdi-info-o")
                            ->schema([
                                Forms\Components\Fieldset::make(
                                    __("dashboard.Quick Facts")
                                )
                                    ->columns(1)
                                    ->schema([
                                        Forms\Components\TextInput::make(
                                            "quick_facts_label"
                                        )
                                            ->label(__("dashboard.Label"))
                                            ->required(),
                                        Forms\Components\RichEditor::make(
                                            "quick_facts_content"
                                        )
                                            ->label(__("dashboard.Content"))
                                            ->required(),
                                    ]),

                                Forms\Components\RichEditor::make(
                                    "details"
                                )->columnSpanFull(),

                                Forms\Components\RichEditor::make(
                                    "how_to_use"
                                )->columnSpanFull(),

                                Forms\Components\RichEditor::make(
                                    "key_ingredients"
                                )->columnSpanFull(),

                                Forms\Components\RichEditor::make(
                                    "full_ingredients"
                                )->columnSpanFull(),

                                Forms\Components\RichEditor::make(
                                    "caution"
                                )->columnSpanFull(),

                                Forms\Components\RichEditor::make(
                                    "how_to_store"
                                )->columnSpanFull(),
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
                                    ProductStatus::PUBLISHED->value
                            )
                            ->displayFormat("d-m-Y-H-i-s"),

                        Toggle::make("is_featured")
                            ->label(__("dashboard.Featured Product"))
                            ->helperText(
                                __(
                                    "dashboard.Only one product can be featured at a time"
                                )
                            )
                            ->default(false),
                    ]),
                    Section::make(__("dashboard.Prices"))->schema([
                        //TODO: add why to currency conversion
                        MoneyInput::make("regular_price")
                            ->label(__("dashboard.Regular Price"))
                            ->required(),
                        MoneyInput::make("sale_price")
                            ->label(__("dashboard.Sale Price"))
                            ->nullable(),

                        Forms\Components\Toggle::make("is_sale_scheduled")
                            ->label(__("dashboard.Schedule Sale"))
                            ->reactive(),

                        Forms\Components\DateTimePicker::make("sale_starts_at")
                            ->label(__("dashboard.Sale Start Date"))
                            ->visible(
                                fn(callable $get) => $get("is_sale_scheduled")
                            ),

                        Forms\Components\DateTimePicker::make("sale_ends_at")
                            ->label(__("dashboard.Sale End Date"))
                            ->visible(
                                fn(callable $get) => $get("is_sale_scheduled")
                            )
                            ->after("sale_starts_at"),
                    ]),

                    Section::make(__("dashboard.Associations"))->schema([
                        SpatieTagsInput::make("tags")->label(
                            __("dashboard.Tags")
                        ),
                        CategoryFilament::Select(
                            CategoryType::PRODUCT,
                            false
                        )->required(),
                        Select::make("product_type")
                            ->multiple()
                            ->createOptionForm([
                                CustomNameSlugField::getCustomTitleField(
                                    label: __("store.Name"),
                                    fieldName: "name"
                                ),
                                CustomNameSlugField::getCustomSlugField(),
                            ])
                            ->required()
                            ->label(__("dashboard.Type"))
                            ->relationship("types", "name")
                            ->preload(),
                    ]),
                    Section::make(__("dashboard.Inventory"))->schema([
                        Forms\Components\Toggle::make("track_quantity")
                            ->label("Stock management")
                            ->helperText(
                                "Track stock quantity for this product"
                            )
                            ->default(true)
                            ->reactive(),

                        Forms\Components\TextInput::make("quantity")
                            ->numeric()
                            ->inlineLabel()
                            ->default(0)
                            ->visible(
                                fn(callable $get) => $get("track_quantity")
                            ),

                        Forms\Components\TextInput::make("low_stock_threshold")
                            ->label(__("dashboard.Low Stock"))
                            ->numeric()
                            ->inlineLabel()
                            ->default(5)
                            ->visible(
                                fn(callable $get) => $get("track_quantity")
                            ),

                        Forms\Components\Select::make("stock_status")
                            ->options(StockStatus::class)
                            ->default(StockStatus::IN_STOCK)
                            ->disabled(
                                fn(callable $get) => $get("track_quantity")
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

                Tables\Columns\TextColumn::make("sku")
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make("regular_price")
                    ->money()
                    ->sortable(),

                Tables\Columns\TextColumn::make("sale_price")
                    ->money()
                    ->sortable(),

                Tables\Columns\IconColumn::make("track_quantity")->boolean(),

                Tables\Columns\TextColumn::make("quantity")->sortable(),

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

                Tables\Filters\TernaryFilter::make("track_quantity"),

                Tables\Filters\TernaryFilter::make("is_sale_scheduled")->label(
                    "On Sale"
                ),
            ])
            ->defaultSort("order")
            ->reorderable("order")
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
        return [
                // Add relations here when needed
            ];
    }

    public static function getPages(): array
    {
        return [
            "index" => Pages\ListProducts::route("/"),
            "create" => Pages\CreateProduct::route("/create"),
            "edit" => Pages\EditProduct::route("/{record}/edit"),
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ["name", "sku"];
    }

    public static function getLabel(): ?string
    {
        return __("dashboard.Product");
    }

    public static function getModelLabel(): string
    {
        return __("dashboard.Product");
    }

    public static function getNavigationLabel(): string
    {
        return __("store.Products");
    }

    public static function getPluralLabel(): ?string
    {
        return __("store.Products");
    }

    public static function getPluralModelLabel(): string
    {
        return __("store.Products");
    }

    public static function getNavigationGroup(): ?string
    {
        return __("store.Shop");
    }
}
