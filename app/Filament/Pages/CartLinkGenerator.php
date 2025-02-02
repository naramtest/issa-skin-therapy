<?php

namespace App\Filament\Pages;

use App\Enums\ProductType;
use App\Models\Bundle;
use App\Models\Product;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\URL;
use Livewire\Attributes\On;

class CartLinkGenerator extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = "heroicon-o-link";
    protected static ?int $navigationSort = 2;
    protected static string $view = "filament.pages.cart-link-generator";

    public ?array $data = [];
    public ?string $generatedUrl = null;

    public static function getNavigationLabel(): string
    {
        return __("store.Cart Link Generator");
    }

    public static function getNavigationGroup(): ?string
    {
        return __("store.Marketing");
    }

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form->schema($this->getFormSchema())->statePath("data");
    }

    protected function getFormSchema(): array
    {
        return [
            Select::make("currency")
                ->options($this->getCurrencyOptions())
                ->columnSpan(1)
                ->label(__("store.Currency")),
            Repeater::make("items")
                ->schema([
                    Select::make("type")
                        ->options(ProductType::class)
                        ->required()
                        ->reactive()
                        ->afterStateUpdated(function ($state, callable $set) {
                            $set("product_id", null);
                        }),

                    Select::make("product_id")
                        ->label("Product")
                        ->options(function (callable $get) {
                            $type = ProductType::tryFrom($get("type"));

                            if (!$type) {
                                return [];
                            }

                            return match ($type) {
                                ProductType::PRODUCT => Product::query()
                                    ->published()
                                    ->get()
                                    ->filter(function ($bundle) {
                                        return $bundle
                                            ->inventory()
                                            ->canBePurchased(1);
                                    })
                                    ->pluck("name", "id"),

                                ProductType::BUNDLE => Bundle::query()
                                    ->published()
                                    ->get()
                                    ->filter(function ($bundle) {
                                        return $bundle
                                            ->inventory()
                                            ->canBePurchased(1);
                                    })
                                    ->pluck("name", "id"),
                            };
                        })
                        ->required()
                        ->reactive()
                        ->searchable()
                        ->preload(),

                    TextInput::make("quantity")
                        ->numeric()
                        ->default(1)
                        ->minValue(1)
                        ->required(),
                ])
                ->defaultItems(1)
                ->columns(3)
                ->itemLabel(function (array $state): ?string {
                    $type = ProductType::tryFrom($state["type"] ?? null);

                    if (!$type) {
                        return null;
                    }

                    $model = match ($type) {
                        ProductType::PRODUCT => Product::find(
                            $state["product_id"] ?? null
                        ),
                        ProductType::BUNDLE => Bundle::find(
                            $state["product_id"] ?? null
                        ),
                    };

                    if (!$model) {
                        return null;
                    }

                    return "{$model->name}";
                })
                ->deleteAction(fn($action) => $action->requiresConfirmation())
                ->addActionLabel("Add Product")
                ->reorderableWithButtons()
                ->collapsible(),
        ];
    }

    protected function getCurrencyOptions(): array
    {
        return collect(config("currency"))
            ->mapWithKeys(function ($currency) {
                return [
                    $currency[
                        "code"
                    ] => "{$currency["name"]} - {$currency["code"]}",
                ];
            })
            ->toArray();
    }

    public function generateUrl(): void
    {
        $items = collect($this->data["items"] ?? []);

        if ($items->isEmpty()) {
            $this->generatedUrl = null;
            return;
        }

        // Build query string for each item
        $queryParams = $items
            ->map(function ($item) {
                return [
                    "type" => $item["type"],
                    "id" => $item["product_id"],
                    "qty" => $item["quantity"],
                ];
            })
            ->toArray();

        $attr = [
            "items" => $queryParams,
        ];
        if ($this->data["currency"]) {
            $attr["currency"] = $this->data["currency"];
        }
        // Generate signed URL that expires in 7 days
        $this->generatedUrl = URL::signedRoute("cart.prefill", $attr);
        //
        //        Notification::make()
        //            ->success()
        //            ->title("Cart link generated successfully!")
        //            ->send();
    }

    #[On("copy-to-clipboard")]
    public function copyToClipboard(): void
    {
        Notification::make()
            ->success()
            ->title("URL copied to clipboard!")
            ->send();
    }
}
