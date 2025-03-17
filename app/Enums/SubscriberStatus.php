<?php

namespace App\Enums;

enum SubscriberStatus: string
{
    case PENDING = "Pending";
    case ACTIVE = "Active";
    case UNSUBSCRIBED = "Unsubscribed";
}
