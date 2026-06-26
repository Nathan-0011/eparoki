<?php
namespace App\Providers\Filament;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id("admin")
            ->path("admin")
            ->login()
            ->brandLogo(asset('images/logo-ekatolik.svg'))
            ->brandLogoHeight('40px')
            ->brandName('')
            ->globalSearch(false)
            ->topNavigation(false)
            ->sidebarCollapsibleOnDesktop(true)
            ->maxContentWidth(\Filament\Support\Enums\MaxWidth::Full)
            ->colors([
                "primary" => Color::Violet,
            ])
            ->darkMode(true)
            ->spa()
            ->navigationGroups([
                \Filament\Navigation\NavigationGroup::make('Liturgi & Ibadah')
                    ->icon('heroicon-o-book-open')
                    ->collapsible(false),
                \Filament\Navigation\NavigationGroup::make('Data Umat')
                    ->icon('heroicon-o-users')
                    ->collapsible(false),
                \Filament\Navigation\NavigationGroup::make('Kepemimpinan')
                    ->icon('heroicon-o-identification')
                    ->collapsible(false),
                \Filament\Navigation\NavigationGroup::make('Konten & Media')
                    ->icon('heroicon-o-photo')
                    ->collapsible(false),
                \Filament\Navigation\NavigationGroup::make('Keuangan & Intensi')
                    ->icon('heroicon-o-heart')
                    ->collapsible(false),
                \Filament\Navigation\NavigationGroup::make('IoT & Display')
                    ->icon('heroicon-o-device-tablet')
                    ->collapsible(false),
            ])
            ->discoverResources(in: app_path("Filament/Resources"), for: "App\\Filament\\Resources")
            ->discoverPages(in: app_path("Filament/Pages"), for: "App\\Filament\\Pages")
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path("Filament/Widgets"), for: "App\\Filament\\Widgets")
            ->widgets([])
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
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
