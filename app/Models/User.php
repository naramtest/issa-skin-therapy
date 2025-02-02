<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;
    use HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        "name",
        "email",
        "password",
        "first_name",
        "last_name",
        "is_admin",
    ];

    // Accessor for full name
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = ["password", "remember_token"];

    // Mutator for setting username from full name if not provided

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($user) {
            if (empty($user->name)) {
                $user->name = strtolower(
                    str_replace(" ", "", $user->first_name . $user->last_name)
                );
            }
        });
        static::updated(function (User $user) {
            if (
                $user->customer &&
                ($user->isDirty("first_name") || $user->isDirty("last_name"))
            ) {
                $user->customer->first_name = $user->first_name;
                $user->customer->last_name = $user->last_name;
                $user->customer->save();
            }
        });
    }

    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function customer(): HasOne
    {
        return $this->hasOne(Customer::class);
    }

    public function scopeAdmins(Builder $query): void
    {
        $query->where("is_admin", "=", 1);
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            "email_verified_at" => "datetime",
            "password" => "hashed",
        ];
    }
}
