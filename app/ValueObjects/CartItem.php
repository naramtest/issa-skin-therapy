<?php

namespace App\ValueObjects;

use App\Contracts\Purchasable;
use App\Enums\ProductType;
use App\Models\Bundle;
use App\Models\Product;
use Livewire\Wireable;
use Money\Currency;
use Money\Money;

class CartItem implements Wireable
{
    public function __construct(
        private readonly string $id,
        public readonly Purchasable $purchasable,
        public int $quantity,
        private readonly array $options = [],
        private readonly ?Money $fixedPrice = null
    ) {
    }

    public static function fromLivewire($value): static
    {
        // Determine the purchasable type and fetch the model
        $purchasable = match ($value["purchasable_type"]) {
            ProductType::PRODUCT->value => Product::findOrFail(
                $value["purchasable_id"]
            ),
            ProductType::BUNDLE->value => Bundle::findOrFail(
                $value["purchasable_id"]
            ),
            default => throw new \InvalidArgumentException(
                "Invalid purchasable type"
            ),
        };

        // Reconstruct the fixed price if it exists
        $fixedPrice = isset($value["fixed_price"])
            ? new Money(
                $value["fixed_price"]["amount"],
                new Currency($value["fixed_price"]["currency"])
            )
            : null;

        return new static(
            $value["id"],
            $purchasable,
            $value["quantity"],
            $value["options"],
            $fixedPrice
        );
    }

    public function getPurchasable(): Purchasable
    {
        return $this->purchasable;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): void
    {
        $this->quantity = $quantity;
    }

    public function getOptions(): array
    {
        return $this->options;
    }

    public function getSubtotal(): Money
    {
        return $this->getPrice()->multiply($this->quantity);
    }

    public function getPrice(): Money
    {
        return $this->fixedPrice ??
            $this->purchasable->getCurrentMoneyPriceAttribute();
    }

    public function toLivewire(): array
    {
        return [
            "id" => $this->id,
            "purchasable_type" =>
                $this->purchasable instanceof Product
                    ? ProductType::PRODUCT->value
                    : ProductType::BUNDLE->value,
            "purchasable_id" => $this->purchasable->getId(),
            "quantity" => $this->quantity,
            "options" => $this->options,
            "fixed_price" => $this->fixedPrice
                ? [
                    "amount" => $this->fixedPrice->getAmount(),
                    "currency" => $this->fixedPrice->getCurrency()->getCode(),
                ]
                : null,
        ];
    }

    public function getId(): string
    {
        return $this->id;
    }
}
