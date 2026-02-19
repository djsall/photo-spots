<?php

namespace App\Providers\Filament;

use Filament\Forms\Components\Field;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Infolists\Components\Entry;
use Filament\Infolists\Components\TextEntry;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\Width;
use Filament\Tables\Columns\Column;
use Filament\Tables\Table;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->registration()
            ->passwordReset()
            ->colors([
                'primary' => Color::Cyan,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
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
            ])
            ->navigationGroups([
                mb_ucfirst(__('admin.navigation.spots')),
                mb_ucfirst(__('admin.navigation.tags')),
                mb_ucfirst(__('admin.navigation.system')),
            ])
            ->bootUsing(function () {
                Field::configureUsing(function (Field $component) {
                    $component->translateLabel();
                });

                Column::configureUsing(function (Column $component) {
                    $component->translateLabel();
                });

                Entry::configureUsing(function (Entry $component) {
                    $component->translateLabel();
                });

                TextEntry::configureUsing(function (TextEntry $component) {
                    $component->placeholder('-');
                });

                Table::configureUsing(function (Table $table) {
                    $table
                        ->paginationPageOptions([10, 25, 50, 100, 'all'])
                        ->persistFiltersInSession()
                        ->persistColumnsInSession();
                });
            })
            ->databaseNotifications()
            ->maxContentWidth(Width::Full);
    }
}
