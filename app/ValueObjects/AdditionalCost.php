<?php

namespace App\ValueObjects;

use Money\Money;

readonly class AdditionalCost
{
    public function __construct(
        public string $type,
        public Money $amount,
        public string $label,
        public bool $taxable = false,
        public bool $subtract = false
    ) {
    }
}
