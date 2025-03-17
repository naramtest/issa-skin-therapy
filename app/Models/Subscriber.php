<?php

namespace App\Models;

use App\Enums\SubscriberStatus;
use Illuminate\Database\Eloquent\Model;

class Subscriber extends Model
{
    protected $fillable = ["email"];

    protected $casts = [
        "status" => SubscriberStatus::class,
    ];
}
