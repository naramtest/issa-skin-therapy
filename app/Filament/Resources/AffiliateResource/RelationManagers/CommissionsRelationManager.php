<?php

namespace App\Filament\Resources\AffiliateResource\RelationManagers;

use App\Enums\CommissionStatus;
use App\Models\AffiliateCommission;
use App\Services\Currency\CurrencyHelper;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Collection;

class CommissionsRelationManager extends RelationManager
{
    protected static string $relationship = "commissionTracks";

    protected static ?string $title = "Commissions";

    public function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make("commission_amount")
                ->label(__("dashboard.Commission Amount"))
                ->disabled()
                ->formatStateUsing(
                    fn($state) => CurrencyHelper::format(
                        new \Money\Money(
                            $state,
                            CurrencyHelper::defaultCurrency()
                        )
                    )
                ),
            Forms\Components\TextInput::make("commission_rate")
                ->label(__("dashboard.Commission Rate"))
                ->disabled()
                ->formatStateUsing(fn($state) => $state . "%"),
            Forms\Components\TextInput::make("order_total")
                ->label(__("dashboard.Order Total"))
                ->disabled()
                ->formatStateUsing(
                    fn($state) => CurrencyHelper::format(
                        new \Money\Money(
                            $state,
                            CurrencyHelper::defaultCurrency()
                        )
                    )
                ),
            Forms\Components\Select::make("status")
                ->label(__("dashboard.Status"))
                ->options([
                    CommissionStatus::PENDING
                        ->value => CommissionStatus::PENDING->getLabel(),
                    CommissionStatus::PAID
                        ->value => CommissionStatus::PAID->getLabel(),
                    CommissionStatus::CANCELED
                        ->value => CommissionStatus::CANCELED->getLabel(),
                ])
                ->disabled(
                    fn(AffiliateCommission $record) => !$record->canBePaid() &&
                        $record->status !== CommissionStatus::PENDING
                )
                ->required(),
            Forms\Components\DateTimePicker::make("paid_at")
                ->label(__("dashboard.Paid At"))
                ->disabled(),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute("id")
            ->columns([
                Tables\Columns\TextColumn::make("order.order_number")
                    ->label(__("dashboard.Order"))
                    ->searchable(),
                Tables\Columns\TextColumn::make("coupon.code")
                    ->label(__("dashboard.Coupon Code"))
                    ->searchable(),
                Tables\Columns\TextColumn::make("money_order_total")
                    ->label(__("dashboard.Order Total"))
                    ->formatStateUsing(
                        fn($state) => CurrencyHelper::format($state)
                    )
                    ->sortable(["order_total"]),
                Tables\Columns\TextColumn::make("commission_rate")
                    ->label(__("dashboard.Rate"))
                    ->formatStateUsing(fn($state) => $state . "%")
                    ->sortable(),
                Tables\Columns\TextColumn::make("money_commission_amount")
                    ->label(__("dashboard.Commission"))
                    ->formatStateUsing(
                        fn($state) => CurrencyHelper::format($state)
                    )
                    ->sortable(["commission_amount"]),
                Tables\Columns\TextColumn::make("status")
                    ->label(__("dashboard.Status"))
                    ->badge()
                    ->formatStateUsing(
                        fn(CommissionStatus $state) => $state->getLabel()
                    )
                    ->color(fn(CommissionStatus $state) => $state->getColor()),
                Tables\Columns\TextColumn::make("created_at")
                    ->label(__("dashboard.Created At"))
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make("paid_at")
                    ->label(__("dashboard.Paid At"))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make("status")->options([
                    CommissionStatus::PENDING
                        ->value => CommissionStatus::PENDING->getLabel(),
                    CommissionStatus::PAID
                        ->value => CommissionStatus::PAID->getLabel(),
                    CommissionStatus::CANCELED
                        ->value => CommissionStatus::CANCELED->getLabel(),
                ]),
            ])
            ->headerActions([
                // No create action - commissions are created automatically
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\Action::make("markAsPaid")
                    ->label(__("dashboard.Mark as Paid"))
                    ->icon("heroicon-o-check-circle")
                    ->color("success")
                    ->requiresConfirmation()
                    ->visible(
                        fn(AffiliateCommission $record) => $record->canBePaid()
                    )
                    ->action(
                        fn(AffiliateCommission $record) => $record->markAsPaid()
                    ),
                Tables\Actions\Action::make("markAsCanceled")
                    ->label(__("dashboard.Mark as Canceled"))
                    ->icon("heroicon-o-x-circle")
                    ->color("danger")
                    ->requiresConfirmation()
                    ->visible(
                        fn(AffiliateCommission $record) => $record->status ===
                            CommissionStatus::PENDING
                    )
                    ->action(
                        fn(
                            AffiliateCommission $record
                        ) => $record->markAsCanceled()
                    ),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make("markAsPaid")
                        ->label(__("dashboard.Mark as Paid"))
                        ->icon("heroicon-o-check-circle")
                        ->color("success")
                        ->requiresConfirmation()
                        ->deselectRecordsAfterCompletion()
                        ->action(function (Collection $records) {
                            foreach ($records as $record) {
                                if ($record->canBePaid()) {
                                    $record->markAsPaid();
                                }
                            }
                        }),
                    Tables\Actions\BulkAction::make("markAsCanceled")
                        ->label(__("dashboard.Mark as Canceled"))
                        ->icon("heroicon-o-x-circle")
                        ->color("danger")
                        ->requiresConfirmation()
                        ->deselectRecordsAfterCompletion()
                        ->action(function (Collection $records) {
                            foreach ($records as $record) {
                                if (
                                    $record->status ===
                                    CommissionStatus::PENDING
                                ) {
                                    $record->markAsCanceled();
                                }
                            }
                        }),
                ]),
            ]);
    }
}
