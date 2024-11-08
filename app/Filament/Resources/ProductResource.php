<?php

namespace App\Filament\Resources;

use App\Enums\StockStatus;
use App\Filament\Resources\ProductResource\Pages;
use App\Models\Product;
use App\Services\Filament\Component\CustomNameSlugField;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Form;
use Filament\Resources\Concerns\Translatable;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ProductResource extends Resource
{
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
                        Tabs\Tab::make("Basic Information")->schema([
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
                                ->label("Permalink"),

                            Forms\Components\TextInput::make("sku")
                                ->label("SKU")
                                ->unique(ignoreRecord: true)
                                ->inlineLabel()
                                ->placeholder(
                                    "Will be generated automatically if left empty"
                                ),

                            Forms\Components\RichEditor::make("description")
                                ->required()
                                ->columnSpanFull(),
                        ]),

                        // Shipping Tab
                        Tabs\Tab::make(__("store.Shipping"))
                            ->columns()
                            ->schema([
                                Forms\Components\TextInput::make("weight")
                                    ->numeric()
                                    ->step(0.001)
                                    ->suffix("kg"),

                                Forms\Components\TextInput::make("length")
                                    ->numeric()
                                    ->step(0.01)
                                    ->suffix("cm"),

                                Forms\Components\TextInput::make("width")
                                    ->numeric()
                                    ->step(0.01)
                                    ->suffix("cm"),

                                Forms\Components\TextInput::make("height")
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
                                    ->options(function () {
                                        // You'll need to implement this with a proper country list
                                        return [
                                            "AE" => "United Arab Emirates",
                                            "US" => "United States",
                                            // Add more countries
                                        ];
                                    }),
                            ]),

                        // Additional Information Tab
                        Tabs\Tab::make(
                            __("dashboard.Additional Information")
                        )->schema([
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
                    ]),
                ])
                ->columnSpan(2),
            Forms\Components\Group::make()
                ->schema([
                    Section::make(__("dashboard.Prices"))->schema([
                        Forms\Components\TextInput::make("regular_price")
                            ->numeric()
                            ->required()
                            ->prefix('$')
                            ->minValue(0.01)
                            ->step(0.01),

                        Forms\Components\TextInput::make("sale_price")
                            ->numeric()
                            ->prefix('$')
                            ->minValue(0.01)
                            ->step(0.01)
                            ->lt("regular_price"),

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
                            ->default(0)
                            ->visible(
                                fn(callable $get) => $get("track_quantity")
                            ),

                        Forms\Components\TextInput::make("low_stock_threshold")
                            ->numeric()
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
     * @throws \Exception
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
}
