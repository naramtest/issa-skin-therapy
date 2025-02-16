<?php

namespace App\Enums\Checkout;

enum PaymentIntentStatus: string
{
    case AUTHORIZED = "AUTHORIZED";
    case EXPIRED = "EXPIRED";
    case REJECTED = "REJECTED";
    case CLOSED = "CLOSED";
    
}