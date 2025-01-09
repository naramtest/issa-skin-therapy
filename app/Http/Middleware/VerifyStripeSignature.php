<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyStripeSignature
{
    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->header("Stripe-Signature")) {
            return response()->json(
                ["error" => "Missing signature header"],
                400
            );
        }

        return $next($request);
    }
}
