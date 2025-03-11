<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactMessage extends Model
{
    protected $fillable = [
        "name",
        "email",
        "phone",
        "subject",
        "message",
        "ip_address",
        "is_read",
    ];

    protected $casts = [
        "is_read" => "boolean",
    ];
}
