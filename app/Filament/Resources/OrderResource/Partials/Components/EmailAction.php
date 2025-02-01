<?php

namespace App\Filament\Resources\OrderResource\Partials\Components;

use App\Enums\Checkout\OrderStatus;
use App\Mail\OrderStatusMail;
use App\Models\Order;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\Action;
use Illuminate\Support\Facades\Mail;

class EmailAction
{
    public static function make(
        string $action,
        string $label,
        OrderStatus $status
    ): Action {
        return Action::make($action)
            ->label($label)
            ->requiresConfirmation()
            ->action(function (Order $record) use ($status) {
                Mail::to($record->email)->queue(
                    new OrderStatusMail($record, $status)
                );
                $record->status = $status;
                $record->save();
                Notification::make()
                    ->success()
                    ->title(__("store.Sending"))
                    ->send();
            });
    }
}
