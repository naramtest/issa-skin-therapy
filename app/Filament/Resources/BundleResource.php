<?php

namespace App\Filament\Resources;

use App\Enums\StockStatus;
use App\Filament\Resources\BundleResource\Pages;
use App\Helpers\Filament\Purchasable\BasicInformation;
use App\Helpers\Filament\Purchasable\MediaSection;
use App\Helpers\Filament\Purchasable\ShippingSection;
use App\Helpers\Filament\Purchasable\StatusSection;
use App\Models\Bundle;
use App\Models\Product;
use Exception;
use Filament\Forms;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Concerns\Translatable;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Pelmered\FilamentMoneyField\Forms\Components\MoneyInput;
use Pelmered\FilamentMoneyField\Tables\Columns\MoneyColumn;

class BundleResource extends Resource
{
    use Translatable;

    //TODO: add preview button to every table column and inside edit page for every resource that has a page

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
                        BasicInformation::make([]),
                        ShippingSection::make([]),

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
                        MediaSection::make(),
                    ]),
                ])
                ->columnSpan(2),

            Forms\Components\Group::make()
                ->schema([
                    // Status Section
                    StatusSection::make([]),

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
                    ->label(__("store.Name"))
                    ->searchable()
                    ->sortable(),

                MoneyColumn::make("regular_price")->label(
                    __("dashboard.Regular Price")
                ),
                MoneyColumn::make("sale_price")->label(
                    __("dashboard.Sale Price")
                ),
                Tables\Columns\TextColumn::make("stock_status")
                    ->badge()
                    ->label(__("dashboard.Stock Status")),

                Tables\Columns\TextColumn::make("created_at")
                    ->label(__("dashboard.Created At"))
                    ->dateTime("M j, Y")
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
