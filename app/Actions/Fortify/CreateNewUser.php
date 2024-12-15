<?php

namespace App\Actions\Fortify;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param array<string, string> $input
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            "first_name" => ["required", "string", "max:255"],
            "last_name" => ["required", "string", "max:255"],
            "email" => [
                "required",
                "string",
                "email",
                "max:255",
                Rule::unique(User::class),
            ],
            "password" => $this->passwordRules(),
        ])->validate();

        $user = User::create([
            "first_name" => $input["first_name"],
            "last_name" => $input["last_name"],
            "email" => $input["email"],
            "password" => Hash::make($input["password"]),
        ]);

        // Assign customer role
        $user->assignRole("customer");

        // Check if there's an existing customer with this email
        $existingCustomer = Customer::where("email", $input["email"])->first();

        if ($existingCustomer) {
            // Link the existing customer to the new user
            $existingCustomer->update([
                "user_id" => $user->id,
                "is_registered" => true,
            ]);
        } else {
            // Create new customer profile
            Customer::create([
                "user_id" => $user->id,
                "first_name" => $input["first_name"],
                "last_name" => $input["last_name"],
                "email" => $input["email"],
                "is_registered" => true,
            ]);
        }

        return $user;
    }
}
