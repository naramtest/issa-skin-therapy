<?php

namespace App\Filament\Resources\OrderResource\Partials;

use App\Filament\Resources\OrderResource\Partials\Components\AddressFieldset;
use App\Filament\Resources\OrderResource\Partials\Components\CustomerTab;
use App\Filament\Resources\OrderResource\Partials\Components\OrderDetailsTab;
use App\Filament\Resources\OrderResource\Partials\Components\PaymentTab;
use App\Helpers\Filament\Component\DateTextInput;
use App\Models\CouponUsage;
use App\Models\Order;
use Awcodes\TableRepeater\Components\TableRepeater;
use Awcodes\TableRepeater\Header;
use Filament\Forms;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ViewField;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Pelmered\FilamentMoneyField\Forms\Components\MoneyInput;

class OrderForm
{
    public static function form(Form $form): Form
    {
        return $form->schema([
            Grid::make([
                "default" => 1,
                "md" => 3,
            ])->schema([
                Tabs::make()
                    ->extraAttributes(["class" => "min-h-[50.5vh]"])
                    ->tabs([
                        OrderDetailsTab::make(),
                        CustomerTab::make(),
                        Tabs\Tab::make(__("store.Addresses"))->schema([
                            AddressFieldset::make(
                                "billingAddress",
                                __("store.Billing")
                            ),
                            AddressFieldset::make(
                                "shippingAddress",
                                __("store.Shipping")
                            ),
                        ]),
                        PaymentTab::make(),
                        Tabs\Tab::make(__("store.Extra"))->schema([
                            Forms\Components\Fieldset::make(
                                __("store.Currency")
                            )
                                ->columns(3)
                                ->schema([
                                    TextInput::make("currency_code")
                                        ->disabled()
                                        ->label(__("store.Currency")),
                                    TextInput::make("base_currency_code")
                                        ->disabled()
                                        ->label(__("store.Base on Currency")),
                                    TextInput::make("exchange_rate")
                                        ->disabled()
                                        ->label(__("store.Currency Rate")),
                                ]),

                            Forms\Components\Fieldset::make(__("store.Coupon"))
                                ->columns(3)
                                ->relationship("couponUsage")
                                ->hidden(function (
                                    string $operation,
                                    Order $record
                                ) {
                                    if (
                                        $operation === "edit" and
                                        $record->couponUsage
                                    ) {
                                        return false;
                                    }
                                    return true;
                                })
                                ->schema([
                                    TextInput::make("coupon.code")
                                        ->label(__("store.Code"))
                                        ->disabled()
                                        ->formatStateUsing(function (
                                            ?CouponUsage $record
                                        ) {
                                            return $record?->coupon->code;
                                        })
                                        ->label(__("store.Currency")),
                                    MoneyInput::make("discount_amount")
                                        ->disabled()
                                        ->label(__("store.Discount Amount")),
                                    DateTextInput::make("used_at")
                                        ->disabled()
                                        ->label(__("store.Used at")),
                                ]),
                        ]),
                    ])
                    ->columnSpan(2),
                Forms\Components\Group::make()
                    ->columnSpan(1)
                    ->schema([
                        Section::make("Status")->schema([]),
                        Section::make(__("store.Summary"))->schema([
                            ViewField::make("billing_summary")
                                ->view("filament.order-billing-summary")
                                ->columnSpanFull(),
                        ]),
                    ]),
            ]),
            TableRepeater::make("items")
                ->headers([
                    Header::make(__("store.Name"))->width("150px"),
                    Header::make("Unit Price")->width("150px"),
                    Header::make(__("store.Subtotal"))->width("150px"),
                    Header::make(__("dashboard.Quantity"))->width("150px"),
                ])
                ->relationship(
                    "items",
                    fn($query) => $query->with("purchasable")
                )
                ->deletable(false)
                ->orderColumn(false)
                ->disabled()
                ->addable(false)
                ->schema([
                    TextInput::make("purchasable.name")
                        ->label(__("store.Name"))
                        ->formatStateUsing(
                            fn($state, $record) => $record->purchasable
                                ?->name ?? "-"
                        ) // Add formatting
                        ->disabled(), //TODO: make it clickable
                    MoneyInput::make("unit_price")
                        ->label(__("store.Unit Price"))
                        ->disabled(),
                    TextInput::make("quantity")
                        ->label(__("dashboard.Quantity"))
                        ->disabled(), //TODO: in the future makes quantity editable
                    MoneyInput::make("subtotal")
                        ->label(__("store.Subtotal"))
                        ->disabled(),
                ])
                ->columnSpan("full"),
        ]);
    }

    public static function newForm(Form $form): Form
    {
        return $form->schema([
            Grid::make([
                "default" => 1,
                "md" => 3,
            ])->schema([
                Tabs::make()
                    ->extraAttributes(["class" => "min-h-[50.5vh]"])
                    ->tabs([
                        OrderDetailsTab::make(),
                        CustomerTab::make(),
                        Tabs\Tab::make(__("store.Addresses"))->schema([
                            AddressFieldset::make(
                                "billingAddress",
                                __("store.Billing")
                            ),
                            AddressFieldset::make(
                                "shippingAddress",
                                __("store.Shipping")
                            ),
                        ]),
                        PaymentTab::make(),
                        Tabs\Tab::make(__("store.Extra"))->schema([
                            Forms\Components\Fieldset::make(
                                __("store.Currency")
                            )
                                ->columns(3)
                                ->schema([
                                    TextInput::make("currency_code")
                                        ->disabled()
                                        ->label(__("store.Currency")),
                                    TextInput::make("base_currency_code")
                                        ->disabled()
                                        ->label(__("store.Base on Currency")),
                                    TextInput::make("exchange_rate")
                                        ->disabled()
                                        ->label(__("store.Currency Rate")),
                                ]),

                            Forms\Components\Fieldset::make(__("store.Coupon"))
                                ->columns(3)
                                ->relationship("couponUsage")
                                ->hidden(function (
                                    string $operation,
                                    Order $record
                                ) {
                                    if (
                                        $operation === "edit" and
                                        $record->couponUsage
                                    ) {
                                        return false;
                                    }
                                    return true;
                                })
                                ->schema([
                                    TextInput::make("coupon.code")
                                        ->label(__("store.Code"))
                                        ->disabled()
                                        ->formatStateUsing(function (
                                            ?CouponUsage $record
                                        ) {
                                            return $record?->coupon->code;
                                        })
                                        ->label(__("store.Currency")),
                                    MoneyInput::make("discount_amount")
                                        ->disabled()
                                        ->label(__("store.Discount Amount")),
                                    DateTextInput::make("used_at")
                                        ->disabled()
                                        ->label(__("store.Used at")),
                                ]),
                        ]),
                    ])
                    ->columnSpan(2),
                Forms\Components\Group::make()
                    ->columnSpan(1)
                    ->schema([
                        Section::make("naram")
                            ->schema([
                                Placeholder::make("created_at")
                                    ->label(__("store.Create At"))
                                    ->inlineLabel()
                                    ->columnSpan(1)
                                    ->content(
                                        fn(
                                            Order $record
                                        ): string => $record->created_at->toFormattedDateString()
                                    ),
                                ViewField::make("status")
                                    ->view(
                                        "filament.forms.components.order-status"
                                    )
                                    ->live()
                                    ->label(__("store.Order Status"))
                                    ->inlineLabel(),
                            ])
                            ->footerActions([
                                //                                TODO: Actions button
                                Action::make("test")
                                    ->label("Test Action")
                                    ->color("success")
                                    ->action(function () {
                                        return Notification::make()
                                            ->title("Action triggered!")
                                            ->success()
                                            ->send();
                                    }),
                            ]),
                        Section::make(__("store.Summary"))->schema([
                            ViewField::make("billing_summary")
                                ->view("filament.order-billing-summary")
                                ->columnSpanFull(),
                        ]),
                    ]),
                TableRepeater::make("items")
                    ->headers([
                        Header::make(__("store.Name"))->width("150px"),
                        Header::make("Unit Price")->width("150px"),
                        Header::make(__("store.Subtotal"))->width("150px"),
                        Header::make(__("dashboard.Quantity"))->width("150px"),
                    ])
                    ->relationship(
                        "items",
                        fn($query) => $query->with("purchasable")
                    )
                    ->deletable(false)
                    ->orderColumn(false)
                    ->disabled()
                    ->addable(false)
                    ->schema([
                        TextInput::make("purchasable.name")
                            ->label(__("store.Name"))
                            ->formatStateUsing(
                                fn($state, $record) => $record->purchasable
                                    ?->name ?? "-"
                            ) // Add formatting
                            ->disabled(), //TODO: make it clickable
                        MoneyInput::make("unit_price")
                            ->label(__("store.Unit Price"))
                            ->disabled(),
                        TextInput::make("quantity")
                            ->label(__("dashboard.Quantity"))
                            ->disabled(), //TODO: in the future makes quantity editable
                        MoneyInput::make("subtotal")
                            ->label(__("store.Subtotal"))
                            ->disabled(),
                    ])
                    ->columnSpan("full"),
            ]),
        ]);
    }
}
