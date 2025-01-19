<?php

namespace App\Filament\Resources\OrderResource\Components;

use App\Enums\Checkout\OrderStatus;
use App\Enums\Checkout\PaymentStatus;
use App\Services\Currency\CurrencyHelper;
use Filament\Forms\Form;
use Money\Money;

class OrderForm
{
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make("Order Details")
                            ->schema([
                                Forms\Components\TextInput::make("order_number")
                                    ->required()
                                    ->maxLength(255)
                                    ->disabled(),
                                Forms\Components\Select::make("status")
                                    ->options(OrderStatus::class)
                                    ->required(),
                                Forms\Components\Select::make("payment_status")
                                    ->options(PaymentStatus::class)
                                    ->required(),
                                Forms\Components\TextInput::make(
                                    "shipping_method"
                                )->maxLength(255),
                                Forms\Components\Textarea::make("notes")
                                    ->maxLength(65535)
                                    ->columnSpanFull(),
                            ])
                            ->columns(2),

                        Forms\Components\Section::make("Items")->schema([
                            Forms\Components\Repeater::make("items")
                                ->relationship()
                                ->schema([
                                    Forms\Components\TextInput::make(
                                        "purchasable_type"
                                    )
                                        ->required()
                                        ->disabled(),
                                    Forms\Components\TextInput::make(
                                        "purchasable_id"
                                    )
                                        ->required()
                                        ->disabled(),
                                    Forms\Components\TextInput::make("quantity")
                                        ->required()
                                        ->numeric()
                                        ->disabled(),
                                    Forms\Components\TextInput::make(
                                        "unit_price"
                                    )
                                        ->required()
                                        ->disabled()
                                        ->formatStateUsing(
                                            fn(
                                                $state
                                            ) => CurrencyHelper::format(
                                                new Money(
                                                    $state,
                                                    CurrencyHelper::defaultCurrency()
                                                )
                                            )
                                        ),
                                    Forms\Components\TextInput::make("subtotal")
                                        ->required()
                                        ->disabled()
                                        ->formatStateUsing(
                                            fn(
                                                $state
                                            ) => CurrencyHelper::format(
                                                new Money(
                                                    $state,
                                                    CurrencyHelper::defaultCurrency()
                                                )
                                            )
                                        ),
                                ])
                                ->disabled()
                                ->columnSpanFull(),
                        ]),
                    ])
                    ->columnSpan(["lg" => 2]),

                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make(
                            "Customer Details"
                        )->schema([
                            Forms\Components\TextInput::make(
                                "customer.full_name"
                            )
                                ->label("Name")
                                ->required()
                                ->disabled(),
                            Forms\Components\TextInput::make("email")
                                ->email()
                                ->required()
                                ->disabled(),
                        ]),

                        Forms\Components\Section::make(
                            "Billing Address"
                        )->schema([
                            Forms\Components\TextInput::make(
                                "billingAddress.full_name"
                            )
                                ->label("Name")
                                ->required()
                                ->disabled(),
                            Forms\Components\TextInput::make(
                                "billingAddress.phone"
                            )
                                ->label("Phone")
                                ->disabled(),
                            Forms\Components\TextInput::make(
                                "billingAddress.address"
                            )->disabled(),
                            Forms\Components\TextInput::make(
                                "billingAddress.city"
                            )->disabled(),
                            Forms\Components\TextInput::make(
                                "billingAddress.state"
                            )->disabled(),
                            Forms\Components\TextInput::make(
                                "billingAddress.country"
                            )->disabled(),
                            Forms\Components\TextInput::make(
                                "billingAddress.postal_code"
                            )->disabled(),
                        ]),

                        Forms\Components\Section::make(
                            "Shipping Address"
                        )->schema([
                            Forms\Components\TextInput::make(
                                "shippingAddress.full_name"
                            )
                                ->label("Name")
                                ->required()
                                ->disabled(),
                            Forms\Components\TextInput::make(
                                "shippingAddress.phone"
                            )
                                ->label("Phone")
                                ->disabled(),
                            Forms\Components\TextInput::make(
                                "shippingAddress.address"
                            )->disabled(),
                            Forms\Components\TextInput::make(
                                "shippingAddress.city"
                            )->disabled(),
                            Forms\Components\TextInput::make(
                                "shippingAddress.state"
                            )->disabled(),
                            Forms\Components\TextInput::make(
                                "shippingAddress.country"
                            )->disabled(),
                            Forms\Components\TextInput::make(
                                "shippingAddress.postal_code"
                            )->disabled(),
                        ]),

                        Forms\Components\Section::make(
                            "Payment Details"
                        )->schema([
                            Forms\Components\TextInput::make(
                                "payment_provider"
                            )->disabled(),
                            Forms\Components\TextInput::make(
                                "payment_intent_id"
                            )->disabled(),
                            Forms\Components\DateTimePicker::make(
                                "payment_authorized_at"
                            )->disabled(),
                            Forms\Components\DateTimePicker::make(
                                "payment_captured_at"
                            )->disabled(),
                            Forms\Components\DateTimePicker::make(
                                "payment_refunded_at"
                            )->disabled(),
                        ]),
                    ])
                    ->columnSpan(["lg" => 1]),
            ])
            ->columns(3);
    }
}
