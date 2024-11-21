<?php

namespace App\Filament\Resources;

use App\Enums\CategoryType;
use App\Enums\StockStatus;
use App\Filament\Resources\ProductResource\Pages;
use App\Helpers\Filament\Component\CategoryFilament;
use App\Helpers\Filament\Component\CustomNameSlugField;
use App\Helpers\Filament\Purchasable\Form\BasicInformation;
use App\Helpers\Filament\Purchasable\Form\MediaSection;
use App\Helpers\Filament\Purchasable\Form\ShippingSection;
use App\Helpers\Filament\Purchasable\Form\StatusSection;
use App\Helpers\Filament\Purchasable\PurchasableTable;
use App\Models\Product;
use Exception;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieTagsInput;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Concerns\Translatable;
use Filament\Resources\Resource;
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
                        BasicInformation::make([
                            Forms\Components\RichEditor::make(
                                "short_description"
                            )
                                ->label(__("dashboard.Short Description"))
                                ->required()
                                ->columnSpanFull(),
                        ]),
                        // Shipping Tab
                        ShippingSection::make([
                            Forms\Components\TextInput::make("hs_code")->label(
                                "HS Code"
                            ),

                            Forms\Components\Select::make("country_of_origin")
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

                                Forms\Components\RichEditor::make("details")
                                    ->label(__("store.Details"))
                                    ->columnSpanFull(),

                                Forms\Components\RichEditor::make("how_to_use")
                                    ->label(__("store.How to use"))
                                    ->columnSpanFull(),

                                Forms\Components\RichEditor::make(
                                    "key_ingredients"
                                )
                                    ->label(__("store.Key Ingredients"))
                                    ->columnSpanFull(),

                                Forms\Components\RichEditor::make(
                                    "full_ingredients"
                                )
                                    ->label(__("dashboard.Full Ingredients"))
                                    ->columnSpanFull(),

                                Forms\Components\RichEditor::make("caution")
                                    ->label(__("store.Caution"))
                                    ->columnSpanFull(),

                                Forms\Components\RichEditor::make(
                                    "how_to_store"
                                )
                                    ->label(__("dashboard.How To Store"))
                                    ->columnSpanFull(),
                            ]),

                        MediaSection::make(),
                    ]),
                ])
                ->columnSpan(2),
            Forms\Components\Group::make()
                ->schema([
                    StatusSection::make([
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
                            ->label(__("dashboard.Stock management"))
                            ->helperText(
                                __(
                                    "dashboard.Track stock quantity for this product"
                                )
                            )
                            ->default(true)
                            ->reactive(),

                        Forms\Components\TextInput::make("quantity")
                            ->label(__("dashboard.Quantity"))
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
            ->columns(PurchasableTable::columns())
            ->filters(PurchasableTable::filters())
            ->defaultSort("order")
            ->reorderable("order")
            ->actions(PurchasableTable::actions())
            ->bulkActions(PurchasableTable::bulkActions());
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
