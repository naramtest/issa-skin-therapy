<?php

namespace App\Providers\Filament;

use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use Exception;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationGroup;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\SpatieLaravelTranslatablePlugin;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Statikbe\FilamentTranslationManager\FilamentChainedTranslationManagerPlugin;

class AdminPanelProvider extends PanelProvider
{
    /**
     * @throws Exception
     */
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id("admin")
            ->path("admin")
            ->sidebarWidth("md")
            ->login()
            ->colors([
                "primary" => Color::Amber,
            ])
            ->plugins([
                SpatieLaravelTranslatablePlugin::make()->defaultLocales([
                    "en",
                    "ar",
                ]),
                FilamentChainedTranslationManagerPlugin::make(),
                FilamentShieldPlugin::make(),
            ])
            ->discoverResources(
                in: app_path("Filament/Resources"),
                for: "App\\Filament\\Resources"
            )
            ->discoverPages(
                in: app_path("Filament/Pages"),
                for: "App\\Filament\\Pages"
            )
            ->pages([Pages\Dashboard::class])
            ->discoverWidgets(
                in: app_path("Filament/Widgets"),
                for: "App\\Filament\\Widgets"
            )
            ->widgets([Widgets\AccountWidget::class])
            ->navigationGroups([
                NavigationGroup::make()->label(
                    fn(): string => __("store.Shop")
                ),
                NavigationGroup::make()->label(
                    fn(): string => __("store.Marketing")
                ),
                NavigationGroup::make()
                    ->label(fn(): string => __("store.Store Settings"))
                    ->collapsed(),
                NavigationGroup::make()
                    ->label(fn(): string => __("dashboard.Content"))
                    ->collapsed(),

                NavigationGroup::make()
                    ->label(fn(): string => __("dashboard.Settings"))
                    ->collapsed(),
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([Authenticate::class])
            ->viteTheme("resources/css/filament/admin/theme.css");
    }
}
