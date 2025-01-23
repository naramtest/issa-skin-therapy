<?php

namespace App\Filament\Resources;

use App\Enums\Checkout\OrderStatus;
use App\Enums\Checkout\PaymentStatus;
use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\Partials\OrderForm;
use App\Models\Order;
use App\Services\Shipping\DHLShipmentService;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Pelmered\FilamentMoneyField\Tables\Columns\MoneyColumn;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;
    protected static ?string $navigationIcon = "gmdi-shopping-cart-o";
    protected static ?int $navigationSort = 4;

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make("order_number")
                    ->label(__("store.Order Number"))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make("customer.full_name")
                    ->label(__("store.Customer"))
                    ->searchable(["first_name", "last_name"]),
                MoneyColumn::make("total")
                    ->sortable()
                    ->label(__("store.Total")),

                Tables\Columns\TextColumn::make("status")->badge()->sortable(),
                Tables\Columns\TextColumn::make("payment_status")->badge(),
                Tables\Columns\TextColumn::make("created_at")
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make("status")->options(
                    OrderStatus::class
                ),
                Tables\Filters\SelectFilter::make("payment_status")->options(
                    PaymentStatus::class
                ),
                Tables\Filters\Filter::make("created_at")
                    ->form([
                        Forms\Components\DatePicker::make("created_from"),
                        Forms\Components\DatePicker::make("created_until"),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data["created_from"],
                                fn(
                                    Builder $query,
                                    $date
                                ): Builder => $query->whereDate(
                                    "created_at",
                                    ">=",
                                    $date
                                )
                            )
                            ->when(
                                $data["created_until"],
                                fn(
                                    Builder $query,
                                    $date
                                ): Builder => $query->whereDate(
                                    "created_at",
                                    "<=",
                                    $date
                                )
                            );
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Action::make("createShipment")
                    ->label("Create DHL Shipment")
                    ->icon("heroicon-o-truck")
                    ->requiresConfirmation()
                    //                    ->hidden(
                    //                        fn(Order $record) => $record->shippingOrder ||
                    //                            $record->payment_status !== "paid" ||
                    //                            $record->status === "cancelled"
                    //                    )
                    ->action(function ($record) {
                        try {
                            $shipmentService = app(DHLShipmentService::class);
                            $shipmentData = $shipmentService->createShipment(
                                $record
                            );

                            $record->shippingOrder()->create([
                                "carrier" => "dhl",
                                "service_code" => $record->shipping_method,
                                "tracking_number" =>
                                    $shipmentData["tracking_number"],
                                "label_url" => $shipmentData["label_url"],
                                "shipping_label_data" =>
                                    $shipmentData["shipping_label_data"],
                                "carrier_response" =>
                                    $shipmentData["raw_response"],
                                "status" => "created",
                                "shipped_at" => now(),
                            ]);

                            Notification::make()
                                ->success()
                                ->title("Shipment Created")
                                ->body(
                                    "Shipment has been created successfully."
                                )
                                ->send();
                        } catch (\Exception $e) {
                            Notification::make()
                                ->danger()
                                ->title("Error")
                                ->body(
                                    "Failed to create shipment: " .
                                        $e->getMessage()
                                )
                                ->send();
                        }
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort("created_at", "desc");
    }

    public static function form(Form $form): Form
    {
        return OrderForm::newForm($form);
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
            "index" => Pages\ListOrders::route("/"),
            "create" => Pages\CreateOrder::route("/create"),
            //            "view" => Pages\ViewOrder::route("/{record}"),
            "edit" => Pages\EditOrder::route("/{record}/edit"),
        ];
    }

    public static function getNavigationLabel(): string
    {
        return __("store.Orders");
    }

    public static function getModelLabel(): string
    {
        return __("store.Order");
    }

    public static function getPluralModelLabel(): string
    {
        return __("store.Orders");
    }

    public static function getNavigationGroup(): ?string
    {
        return __("store.Shop");
    }

    public static function getLabel(): ?string
    {
        return __("store.Order");
    }

    public static function getPluralLabel(): ?string
    {
        return __("store.orders");
    }
}
