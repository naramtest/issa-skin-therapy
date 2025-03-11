<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;

class TurnstileCheckRule implements ValidationRule
{
    public function validate(
        string $attribute,
        mixed $value,
        Closure $fail
    ): void {
        try {
            $response = Http::asForm()->post(
                "https://challenges.cloudflare.com/turnstile/v0/siteverify",
                [
                    "secret" => config("services.cloudflare.secret_key"),
                    "response" => $value,
                    "remoteip" => request()->ip(),
                ]
            );
            $result = $response->json();
            if (!$result["success"]) {
                $fail(__("Please verify you are not a robot."));
            }
        } catch (ConnectionException $e) {
            $fail(__("Please verify you are not a robot."));
        }
    }
}
