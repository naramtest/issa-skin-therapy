<?php

namespace App\Http\Middleware;

use App\Services\Currency\LocationDetectionService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DetectUserCurrency
{
    public function __construct(
        protected LocationDetectionService $locationService
    ) {
    }

    public function handle(Request $request, Closure $next): Response
    {
        if (
            $request->is("api/*") ||
            session()->has("currency_manually_selected")
        ) {
            return $next($request);
        }

        // Detect and set currency if not already set
        if (!session()->has("currency")) {
            $this->locationService->detectAndSetUserCurrency();
        }
        return $next($request);
    }
}
