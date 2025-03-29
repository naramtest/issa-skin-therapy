<?php

namespace App\Helpers\Filament\Coupon;

use App\Enums\CouponType;
use App\Models\Country;
use App\Models\Coupon;
use App\Services\Currency\CurrencyHelper;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Money\Money;
use Pelmered\FilamentMoneyField\Forms\Components\MoneyInput;

class CouponForm
{
    public static function make(Form $form, array $additionalFields = []): Form
    {
        return $form->schema([
            Forms\Components\Section::make()
                ->schema([
                    Forms\Components\TextInput::make("code")
                        ->required()
                        ->label(__("store.Code"))
                        ->unique(ignoreRecord: true)
                        ->maxLength(255),

                    Forms\Components\Select::make("discount_type")
                        ->options(CouponType::class)
                        ->label(__("dashboard.Discount Type"))
                        ->live()
                        ->required(),

                    self::discountAmountInput(),

                    MoneyInput::make("minimum_spend")
                        ->required()
                        ->label(__("dashboard.Minimum Spend"))
                        ->default(0)
                        ->numeric()
                        ->minValue(0),
                    MoneyInput::make("maximum_spend")
                        ->label(__("dashboard.Maximum Spend"))
                        ->numeric()
                        ->minValue(0)
                        ->gt("minimum_spend"),

                    Forms\Components\TextInput::make("usage_limit")
                        ->numeric()
                        ->label(__("dashboard.Usage Limit"))
                        ->minValue(1),

                    Forms\Components\DateTimePicker::make("starts_at")->label(
                        __("dashboard.Starts At")
                    ),
                    Forms\Components\DateTimePicker::make("expires_at")
                        ->minDate(now())
                        ->label(__("dashboard.Expires at")),

                    Forms\Components\Toggle::make("is_active")
                        ->required()
                        ->label(__("store.Active"))
                        ->default(true),
                    Forms\Components\Toggle::make("includes_free_shipping")
                        ->label(__("dashboard.Include Free Shipping"))
                        ->live(),

                    Forms\Components\Select::make("allowed_shipping_countries")
                        ->label(
                            __("dashboard.Restrict Free Shipping to Countries")
                        )
                        ->multiple()
                        ->searchable()
                        ->options(function () {
                            return Country::pluck("name", "iso2")->toArray();
                        })
                        ->visible(
                            fn(Get $get) => $get("includes_free_shipping")
                        ),
                    ...$additionalFields,
                    Forms\Components\Textarea::make("description")
                        ->label(__("dashboard.Description"))
                        ->maxLength(65535)
                        ->columnSpanFull(),
                ])
                ->columns(),
        ]);
    }

    /**
     * @return Forms\Components\TextInput
     */
    public static function discountAmountInput(): Forms\Components\TextInput
    {
        return Forms\Components\TextInput::make("discount_amount")
            ->required()
            ->numeric()
            ->label(__("store.Discount Amount"))
            ->minValue(0)
            ->prefix(function (Forms\Get $get) {
                return $get("discount_type") == CouponType::PERCENTAGE->value
                    ? "%"
                    : config("app.money_currency");
            })
            ->formatStateUsing(function ($state, ?Coupon $record = null) {
                if (!$record) {
                    return null;
                }

                if ($record->discount_type === CouponType::PERCENTAGE) {
                    return $state;
                }

                // Convert from subunits back to decimal for display
                return CurrencyHelper::decimalFormatter(
                    new Money($state, CurrencyHelper::defaultCurrency())
                );
            })
            ->dehydrateStateUsing(function ($state, Forms\Get $get) {
                if ($get("discount_type") === CouponType::PERCENTAGE->value) {
                    return $state;
                }

                // Always convert to subunits during form submission
                return CurrencyHelper::convertToSubunits(
                    floatval($state),
                    CurrencyHelper::defaultCurrency()->getCode()
                );
            });
    }
}
