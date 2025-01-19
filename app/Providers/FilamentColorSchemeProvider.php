<?php

// App/Providers/FilamentColorSchemeProvider.php

namespace App\Providers;

use Filament\Support\Colors\Color;
use Filament\Support\Facades\FilamentColor;
use Illuminate\Support\ServiceProvider;

class FilamentColorSchemeProvider extends ServiceProvider
{
    public function register(): void
    {
        FilamentColor::register([
            "orange" => Color::Orange,
            "custom-orange" => [
                50 => "#fff7ed",
                100 => "#ffedd5",
                200 => "#fed7aa",
                300 => "#fdba74",
                400 => "#fb923c",
                500 => "#f97316",
                600 => "#ea580c",
                700 => "#c2410c",
                800 => "#9a3412",
                900 => "#7c2d12",
                950 => "#431407",
            ],
        ]);
    }
}
