<?php

namespace App\Filament\Resources\CustomerResource\RelationManagers;

use App\Enums\AddressType;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class AddressesRelationManager extends RelationManager
{
    protected static string $relationship = "addresses";

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute("type")
            ->columns([
                Tables\Columns\TextColumn::make("type")->badge(),
                Tables\Columns\IconColumn::make("is_default")->boolean(),
                Tables\Columns\TextColumn::make("full_name")->label("Name"),
                Tables\Columns\TextColumn::make("phone"),
                Tables\Columns\TextColumn::make("full_address")
                    ->label("Address")
                    ->limit(50),
                Tables\Columns\TextColumn::make("last_used_at")->dateTime(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make("type")->options(
                    AddressType::class
                ),
                Tables\Filters\TernaryFilter::make("is_default")->label(
                    "Default Addresses"
                ),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\Action::make("setDefault")
                    ->label("Set as Default")
                    ->icon("heroicon-o-star")
                    ->hidden(fn($record) => $record->is_default)
                    ->action(function ($record) {
                        // First, remove default status from all other addresses of this type
                        $this->getOwnerRecord()
                            ->addresses()
                            ->where("type", $record->type)
                            ->update(["is_default" => false]);

                        // Set this address as default
                        $record->update(["is_default" => true]);
                    }),
            ])
            ->bulkActions([Tables\Actions\BulkActionGroup::make([])])
            ->defaultSort("last_used_at", "desc");
    }
}
