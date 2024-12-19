<?php

namespace App\Contracts;

use Money\Money;

interface CartInterface
{
    public function getId(): string;

    public function getItems(): array;

    public function getSubtotal(): Money;

    public function getTotal(): Money;

    public function isEmpty(): bool;

    public function itemCount(): int;

    public function addItem(
        string $productId,
        int $quantity = 1,
        array $options = []
    ): void;

    public function removeItem(string $itemId): void;

    public function updateItemQuantity(string $itemId, int $quantity): void;

    public function clear(): void;

    public function exists(): bool;
}
