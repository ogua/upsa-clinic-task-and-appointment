<?php

namespace App\Providers\Filament;

use App\Filament\Pages\Auth\Login;
use EightyNine\Reports\ReportsPlugin;
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
use lockscreen\FilamentLockscreen\Http\Middleware\Locker;
use Swis\Filament\Backgrounds\FilamentBackgroundsPlugin;
use Swis\Filament\Backgrounds\ImageProviders\MyImages;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
        ->default()
        ->id('admin')
        ->path('admin')
        ->login()
        ->profile()
        ->login(Login::class)
        ->colors([
            'primary' => Color::Amber,
            ])
            ->plugins([
                FilamentBackgroundsPlugin::make()
                ->imageProvider(
                    MyImages::make()
                    ->directory('images/upsa')
                    )
                    ->showAttribution(false),
                    ReportsPlugin::make(),
                    ])
                    ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
                    ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
                    ->pages([
                        Pages\Dashboard::class,
                        ])
                        ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
                        ->widgets([
                            Widgets\AccountWidget::class,
                            // Widgets\FilamentInfoWidget::class,
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
                                ->authMiddleware([
                                    Authenticate::class,
                                    //Locker::class,
                                ]);
                            }
                        }
                        