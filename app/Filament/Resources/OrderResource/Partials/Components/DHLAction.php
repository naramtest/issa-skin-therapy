<?php

namespace App\Filament\Resources\OrderResource\Partials\Components;

use App\Enums\Checkout\OrderStatus;
use App\Enums\Checkout\PaymentStatus;
use App\Models\Order;
use App\Services\Shipping\DHL\DHLShipmentService;
use Filament\Notifications\Notification;

class DHLAction
{
    public static function action(Order $order): void
    {
        try {
            $shipmentService = app(DHLShipmentService::class);

            $shipmentService->createDHLShippingOrder($order);

            Notification::make()
                ->success()
                ->title(__("store.Shipment Created"))
                ->body(__("store.Shipment has been created successfully"))
                ->send();
        } catch (\Exception $e) {
            Notification::make()
                ->danger()
                ->title(__("store.Error"))
                ->body("Failed to create shipment: " . $e->getMessage())
                ->send();
        }
    }

    public static function hidden(Order $order): bool
    {
        return !$order->dhl_product ||
            $order->shippingOrder ||
            $order->payment_status !== PaymentStatus::PAID ||
            $order->status === OrderStatus::CANCELLED;
    }
}
