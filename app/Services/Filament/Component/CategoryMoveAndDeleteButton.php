<?php

namespace App\Services\Filament\Component;

use App\Enums\CategoryType;
use App\Models\Category;
use App\Models\Product;
use Auth;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Filament\Support\Enums\Alignment;
use Filament\Tables\Actions\Action;

class CategoryMoveAndDeleteButton
{
    public static function make()
    {
        return Action::make("Delete")
            ->label(__("dashboard.Delete"))
            ->form([
                Select::make("category")
                    ->options(function (Category $record) {
                        return Category::select(["name", "id", "slug"])
                            ->whereNot("id", $record->id)
                            ->where("type", $record->type)
                            ->pluck("name", "id");
                    })
                    ->default("general")
                    ->required()
                    ->hiddenLabel(),
            ])
            ->color("danger")
            ->modalWidth("md")
            ->icon("heroicon-m-trash")
            ->modalIcon("heroicon-o-trash")
            ->modalAlignment(Alignment::Center)
            ->modalHeading(__("dashboard.Delete Category"))
            ->modalDescription(
                __(
                    "dashboard.This Category Has Items. Please Select a Category From the Dropdown to Move Them."
                )
            )
            ->action(function (array $data, Category $record): void {
                if ($record->type === CategoryType::PRODUCT) {
                    $products = Product::whereHas(
                        "categories",
                        fn($query) => $query->where("category_id", $record->id)
                    )->get();
                    // TODO: check if the attach works
                    $products->each(
                        fn($product) => $product
                            ->categories()
                            ->attach($data["category"])
                    );
                } elseif ($record->type === CategoryType::POST) {
                    //TODO: add Post Here
                }

                if ($record->delete()) {
                    Notification::make()
                        ->success()
                        ->title(
                            __(
                                "filament-actions::delete.single.notifications.deleted.title"
                            )
                        )
                        ->send();
                }
            })
            ->modalSubmitActionLabel(__("dashboard.Move & Delete"))
            ->hidden(
                fn(Category $record) => !Auth::user()->can("delete", $record) or
                    !$record->products()->exists()
            );
    }
}
