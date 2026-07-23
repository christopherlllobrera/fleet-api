<?php

namespace App\Providers\Filament;

use Filament\Actions\Action;
use Filament\Auth\MultiFactor\App\AppAuthentication;
use Filament\Auth\MultiFactor\Email\EmailAuthentication;
use Filament\Enums\ThemeMode;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use App\Filament\Pages\FleetDashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\Width;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Joaopaulolndev\FilamentEditProfile\FilamentEditProfilePlugin;
use Joaopaulolndev\FilamentEditProfile\Pages\EditProfilePage;

use App\Filament\Widgets\TripOverview;
use App\Filament\Widgets\DispatchChart;
use App\Filament\Widgets\VehicleOverview;
use App\Filament\Widgets\PassengerChart;
use App\Filament\Widgets\LatestIncident;

class FleetPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('fleet')
            ->path('fleet')
            ->login()
            ->profile()
            ->passwordReset()
            ->registration()
            ->registrationRouteSlug('register')
            ->emailVerificationRoutePrefix('email-verification')
            ->emailChangeVerificationRouteSlug('verify')
            ->emailVerificationRouteSlug('verify')
            ->topbar(false)
            ->spa()
            // ->favicon(asset('favicon-official-1.png'))
            ->brandName('IAM')
            ->brandLogoHeight('3rem')
            ->darkModeBrandLogo(fn () => view('filament.customization.logodarkmode'))
            ->brandLogo(fn () => view('filament.customization.logo'))
            ->multiFactorAuthentication([
                AppAuthentication::make()
                    ->brandName('Auth Demo')
                    ->recoverable()
                    ->recoveryCodeCount(10),
                EmailAuthentication::make()
                    ->codeExpiryMinutes(2),

            ])
            // ->maxContentWidth(Width::Full)
            ->simplePageMaxContentWidth(Width::Small)
            ->databaseNotifications()
            ->colors([
                'primary' => Color::Amber,
            ])
            ->globalSearch(false)
            // ->sidebarFullyCollapsibleOnDesktop()
            ->sidebarCollapsibleOnDesktop()
            ->defaultThemeMode(ThemeMode::Light)
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverClusters(in: app_path('Filament/Clusters'), for: 'App\\Filament\\Clusters')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                // Dashboard::class,
                FleetDashboard::class,
            ])
            ->navigationGroups([
                'Fleet',
                'Workforce',
                'Data',
                'Settings',
            ])
            // ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([
                TripOverview::class,
                DispatchChart::class,
                // PassengerChart::class,
                // VehicleOverview::class,
                // LatestIncident::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                PreventRequestForgery::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->viteTheme('resources/css/filament/app/theme.css')
            ->plugins([
                FilamentEditProfilePlugin::make()
                    ->slug('my-profile')
                    ->setTitle('My Profile')
                    ->setNavigationLabel('My Profile')
                    ->setNavigationGroup('Group Profile')
                    ->setIcon('heroicon-o-user')
                    ->setSort(10)
                    // ->canAccess(fn () => auth()->user()->id === 1)
                    ->shouldRegisterNavigation(false)
                    ->shouldShowEmailForm()
                    ->shouldShowAvatarForm()
                    ->shouldShowMultiFactorAuthentication(),
            ])
            ->userMenuItems([
                'profile' => Action::make('profile')
                    ->label('Profile')
                    ->url(fn (): string => EditProfilePage::getUrl())
                    ->icon('heroicon-m-user-circle'),
                // If you are using tenancy need to check with the visible method where ->company() is the relation between the user and tenancy model as you called
                // ->visible(function (): bool {
                //     return auth()->user()->company()->exists();
                // }),
            ]);
    }
}
