<?php

namespace App\Enums\Checkout;

enum PaymentMethod: string
{
    case CARD = "card";
    case TABBY = "tabby";
}
