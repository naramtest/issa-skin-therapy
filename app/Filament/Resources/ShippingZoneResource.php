<?php

namespace App\Filament\Resources;

use App\Enums\Checkout\ShippingMethodType;
use App\Filament\Resources\ShippingZoneResource\Pages;
use App\Models\Country;
use App\Models\ShippingZone;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Pelmered\FilamentMoneyField\Forms\Components\MoneyInput;

class ShippingZoneResource extends Resource
{
    protected static ?string $model = ShippingZone::class;

    protected static ?string $navigationIcon = "gmdi-local-shipping-o";

    protected static ?int $navigationSort = 5;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make(__("store.Details"))
                ->schema([
                    Forms\Components\TextInput::make("name")
                        ->label(__("store.Name"))
                        ->required()
                        ->maxLength(255),

                    Forms\Components\Toggle::make("is_all_countries")
                        ->label(__("store.All Countries"))
                        ->inline(false)
                        ->live(),

                    Forms\Components\Select::make("countries")
                        ->label(__("store.Countries"))
                        ->multiple()
                        ->searchable()
                        ->options(function () {
                            return Country::query()
                                ->active()
                                ->orderBy("name")
                                ->pluck("name", "iso2");
                        })
                        ->hidden(fn(Forms\Get $get) => $get("is_all_countries"))
                        ->required(
                            fn(Forms\Get $get) => !$get("is_all_countries")
                        ),

                    Forms\Components\Toggle::make("is_active")
                        ->default(true)
                        ->inline(false)
                        ->label(__("store.Active")),
                ])
                ->columnSpan(1)
                ->columns(1),
            Forms\Components\Repeater::make("methods")
                ->relationship()
                ->schema([
                    Forms\Components\Select::make("method_type")
                        ->options(ShippingMethodType::class)
                        ->required()
                        ->live(),

                    Forms\Components\TextInput::make("title")
                        ->required()
                        ->maxLength(255),

                    MoneyInput::make("cost")
                        ->required(
                            fn(Forms\Get $get) => $get("method_type") ===
                                ShippingMethodType::FLAT_RATE->value
                        )
                        ->hidden(
                            fn(Forms\Get $get) => $get("method_type") ===
                                ShippingMethodType::FREE_SHIPPING->value
                        ),

                    Forms\Components\Grid::make()
                        ->schema(
                            fn(Forms\Get $get) => self::getMethodSettingsFields(
                                $get("method_type")
                            )
                        )
                        ->columns(2),

                    Forms\Components\Toggle::make("is_active")->default(true),
                ])
                ->orderColumn("order")
                ->defaultItems(1)
                ->reorderable()
                ->collapsible()
                ->collapseAllAction(
                    fn(
                        Forms\Components\Actions\Action $action
                    ) => $action->label(__("dashboard.Collapse All"))
                )
                ->addActionLabel(__("store.Add Shipping Method")),
        ]);
    }

    protected static function getMethodSettingsFields($methodType): array
    {
        if (!$methodType) {
            return [];
        }

        return match ($methodType) {
            ShippingMethodType::FREE_SHIPPING->value => [
                MoneyInput::make("settings.minimum_order_amount")
                    ->label(__("store.Minimum Order Amount"))
                    ->required(),
            ],
            ShippingMethodType::FLAT_RATE->value => [
                Forms\Components\Select::make("settings.calculation_type")
                    ->options([
                        "per_order" => __("store.Per Order"),
                        "per_item" => __("store.Per Item"),
                    ])
                    ->default("per_order")
                    ->required(),
                MoneyInput::make("settings.minimum_order_amount")->label(
                    __("store.Minimum Order Amount")
                ),
            ],
            //            ShippingMethodType::DHL_EXPRESS->value => [
            //                MoneyInput::make("settings.handling_fee")->label(
            //                    __("store.Handling Fee")
            //                ),
            //                MoneyInput::make("settings.minimum_order_amount")->label(
            //                    __("store.Minimum Order Amount")
            //                ),
            //                Forms\Components\Select::make("settings.allowed_services")
            //                    ->multiple()
            //                    ->options([
            //                        "express" => __("store.Express"),
            //                        "economy" => __("store.Economy"),
            //                    ]),
            //            ],
            default => [],
        };
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make("name")->searchable(),

                Tables\Columns\IconColumn::make("is_all_countries")->boolean(),

                Tables\Columns\TextColumn::make("countries")
                    ->formatStateUsing(function ($state) {
                        if (empty($state)) {
                            return "-";
                        }
                        return collect($state)
                            ->map(function ($code) {
                                return config("countries.$code.name", $code);
                            })
                            ->join(", ");
                    })
                    ->searchable(),

                Tables\Columns\TextColumn::make("methods_count")
                    ->counts("methods")
                    ->label(__("store.Methods")),

                Tables\Columns\IconColumn::make("is_active")->boolean(),

                Tables\Columns\TextColumn::make("created_at")
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make("is_active"),
                Tables\Filters\TernaryFilter::make("is_all_countries"),
            ])
            ->actions([Tables\Actions\EditAction::make()])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort("order");
    }

    public static function getRelations(): array
    {
        return [
                //
            ];
    }

    public static function getPages(): array
    {
        return [
            "index" => Pages\ListShippingZones::route("/"),
            "create" => Pages\CreateShippingZone::route("/create"),
            "edit" => Pages\EditShippingZone::route("/{record}/edit"),
        ];
    }

    public static function getLabel(): ?string
    {
        return __("store.Shipping Zone");
    }

    public static function getModelLabel(): string
    {
        return __("store.Shipping Zone");
    }

    public static function getNavigationLabel(): string
    {
        return __("store.Shipping Zones");
    }

    public static function getPluralLabel(): ?string
    {
        return __("store.Shipping Zones");
    }

    public static function getPluralModelLabel(): string
    {
        return __("store.Shipping Zones");
    }

    public static function getNavigationGroup(): ?string
    {
        return __("store.Store Settings");
    }
}
