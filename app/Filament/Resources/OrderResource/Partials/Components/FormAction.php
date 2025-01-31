<?php

namespace App\Filament\Resources\OrderResource\Partials\Components;

use App\Enums\Checkout\OrderStatus;
use App\Models\Order;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Set;
use Filament\Notifications\Notification;

class FormAction
{
    public static function actions(): array
    {
        return [
            self::action(
                OrderStatus::COMPLETED,
                "complete",
                __("store.Complete")
            ),
            self::action(OrderStatus::CANCELLED, "cancel", __("store.Cancel")),
            self::action(OrderStatus::ON_HOLD, "hold", __("store.On Hold")),
        ];
    }

    public static function action(
        OrderStatus $orderStatus,
        string $name,
        string $label
    ) {
        return Action::make($name)
            ->label($label)
            ->extraAttributes(["class" => "flex-1"])
            ->requiresConfirmation()
            ->disabled(fn(Order $record) => $record->status == $orderStatus)
            ->color($orderStatus->getColor())
            ->action(function (Order $record, Set $set) use ($orderStatus) {
                $record->status = $orderStatus;
                if ($record->save()) {
                    $set("status", $orderStatus->value);
                    return Notification::make()
                        ->title(__("store.Done"))
                        ->success()
                        ->send();
                }
                return Notification::make()
                    ->title(__("store.Something went wrong! try again later"))
                    ->danger()
                    ->send();
            });
    }
}
