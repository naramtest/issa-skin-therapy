<?php

namespace App\Filament\Resources;

use App\Enums\CouponType;
use App\Filament\Resources\CouponResource\Pages;
use App\Models\Country;
use App\Models\Coupon;
use App\Services\Currency\CurrencyHelper;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Money\Money;
use Pelmered\FilamentMoneyField\Forms\Components\MoneyInput;

class CouponResource extends Resource
{
    //   TODO: translate and organize
    //    TODO: add 1- Fixed Product Discount
    // (3 items in cart 20$ each if the discount was 10$ the total discount is 30$)
    //    TODO: add products and category restriction
    //    TODO: add a button to auto generate Coupon
    //    TODO: add way to show the orders that coupon is used in (CouponUsage modal)
    protected static ?string $model = Coupon::class;

    protected static ?string $navigationIcon = "gmdi-local-offer-o";

    protected static ?int $navigationSort = 1;

    /**
     * @throws \Exception
     */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make("code")->searchable(),

                Tables\Columns\TextColumn::make("discount_type")->badge(),

                Tables\Columns\TextColumn::make("discount_amount")
                    ->sortable()
                    ->formatStateUsing(function ($state, Model $record) {
                        if (
                            $record->discount_type ===
                            CouponType::PERCENTAGE->value
                        ) {
                            return $state . "%";
                        }

                        return CurrencyHelper::format(
                            new Money($state, CurrencyHelper::defaultCurrency())
                        );
                    }),

                Tables\Columns\TextColumn::make("used_count")->sortable(),

                Tables\Columns\TextColumn::make("usage_limit")->sortable(),

                Tables\Columns\IconColumn::make("is_active")->boolean(),

                Tables\Columns\TextColumn::make("expires_at")
                    ->dateTime()
                    ->sortable(),

                Tables\Columns\TextColumn::make("created_at")
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make("discount_type")->options(
                    CouponType::class
                ),

                Tables\Filters\TernaryFilter::make("is_active"),
                //                TODO: add date Range for expire at
            ])
            ->actions([Tables\Actions\EditAction::make()])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make()
                ->schema([
                    Forms\Components\TextInput::make("code")
                        ->required()
                        ->unique(ignoreRecord: true)
                        ->maxLength(255),

                    Forms\Components\Select::make("discount_type")
                        ->options(CouponType::class)
                        ->live()
                        ->required(),

                    Forms\Components\TextInput::make("discount_amount")
                        ->required()
                        ->numeric()
                        ->minValue(0)
                        ->prefix(function (Forms\Get $get) {
                            return $get("discount_type") ==
                                CouponType::PERCENTAGE->value
                                ? "%"
                                : config("app.money_currency");
                        })
                        ->formatStateUsing(function (
                            $state,
                            ?Model $record = null
                        ) {
                            if (!$record) {
                                return null;
                            }

                            if (
                                $record->discount_type ===
                                CouponType::PERCENTAGE
                            ) {
                                return $state;
                            }

                            // Convert from subunits back to decimal for display
                            return CurrencyHelper::decimalFormatter(
                                new Money(
                                    $state,
                                    CurrencyHelper::defaultCurrency()
                                )
                            );
                        })
                        ->dehydrateStateUsing(function (
                            $state,
                            Forms\Get $get
                        ) {
                            if (
                                $get("discount_type") ===
                                CouponType::PERCENTAGE->value
                            ) {
                                return $state;
                            }

                            // Always convert to subunits during form submission
                            return CurrencyHelper::convertToSubunits(
                                floatval($state),
                                CurrencyHelper::defaultCurrency()->getCode()
                            );
                        }),

                    MoneyInput::make("minimum_spend")->required(),
                    MoneyInput::make("maximum_spend"),

                    Forms\Components\TextInput::make("usage_limit")
                        ->numeric()
                        ->minValue(1),

                    Forms\Components\DateTimePicker::make("starts_at"),
                    Forms\Components\DateTimePicker::make("expires_at"),

                    Forms\Components\Toggle::make("is_active")
                        ->required()
                        ->default(true),
                    Forms\Components\Toggle::make("includes_free_shipping")
                        ->label("Include Free Shipping")
                        ->live(),

                    Forms\Components\Select::make("allowed_shipping_countries")
                        ->label("Restrict Free Shipping to Countries")
                        ->multiple()
                        ->searchable()
                        ->options(function () {
                            return Country::pluck("name", "iso2")->toArray();
                        })
                        ->visible(
                            fn(Get $get) => $get("includes_free_shipping")
                        ),

                    Forms\Components\Textarea::make("description")
                        ->maxLength(65535)
                        ->columnSpanFull(),
                ])
                ->columns(2),
        ]);
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
            "index" => Pages\ListCoupons::route("/"),
            "create" => Pages\CreateCoupon::route("/create"),
            "edit" => Pages\EditCoupon::route("/{record}/edit"),
        ];
    }

    public static function getLabel(): ?string
    {
        return __("store.Coupon");
    }

    public static function getModelLabel(): string
    {
        return __("store.Coupon");
    }

    public static function getNavigationLabel(): string
    {
        return __("store.Coupons");
    }

    public static function getPluralLabel(): ?string
    {
        return __("store.Coupons");
    }

    public static function getPluralModelLabel(): string
    {
        return __("store.Coupons");
    }

    public static function getNavigationGroup(): ?string
    {
        return __("store.Marketing");
    }
}
