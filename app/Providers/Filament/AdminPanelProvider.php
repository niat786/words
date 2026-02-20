<?php

namespace App\Providers\Filament;

use App\Models\Setting;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\MenuItem;
use Filament\Navigation\NavigationItem;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Support\Icons\Heroicon;
use Filament\View\PanelsRenderHook;
use Filament\Widgets\AccountWidget;
use Illuminate\Database\QueryException;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    protected ?Setting $cachedSettings = null;

    protected bool $hasResolvedSettings = false;

    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->viteTheme('resources/css/filament/admin/theme.css')
            ->login()
            ->brandName(fn (): string => $this->resolveBrandName())
            ->brandLogo(fn (): ?string => $this->resolveBrandLogoUrl())
            ->darkModeBrandLogo(fn (): ?string => $this->resolveBrandLogoUrl())
            ->brandLogoHeight('2rem')
            ->favicon(fn (): ?string => $this->resolveFaviconUrl())
            ->colors([
                'primary' => Color::Amber,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->navigationItems([
               
                NavigationItem::make('Profile')
                    ->group('Account')
                    ->icon(Heroicon::OutlinedUserCircle)
                    ->url(fn (): string => route('profile.edit'))
                    ->isActiveWhen(fn (): bool => request()->routeIs('profile.edit'))
                    ->sort(1),
                NavigationItem::make('Password')
                    ->group('Account')
                    ->icon(Heroicon::OutlinedKey)
                    ->url(fn (): string => route('user-password.edit'))
                    ->isActiveWhen(fn (): bool => request()->routeIs('user-password.edit'))
                    ->sort(2),
                NavigationItem::make('Appearance')
                    ->group('Account')
                    ->icon(Heroicon::OutlinedSwatch)
                    ->url(fn (): string => route('appearance.edit'))
                    ->isActiveWhen(fn (): bool => request()->routeIs('appearance.edit'))
                    ->sort(3),
            ])
            ->userMenuItems([
               
                MenuItem::make()
                    ->label('Profile')
                    ->icon(Heroicon::OutlinedUserCircle)
                    ->url(fn (): string => route('profile.edit'))
                    ->sort(1),
                MenuItem::make()
                    ->label('Password')
                    ->icon(Heroicon::OutlinedKey)
                    ->url(fn (): string => route('user-password.edit'))
                    ->sort(2),
                MenuItem::make()
                    ->label('Appearance')
                    ->icon(Heroicon::OutlinedSwatch)
                    ->url(fn (): string => route('appearance.edit'))
                    ->sort(3),
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([
                AccountWidget::class,
            ])
            ->renderHook(
                PanelsRenderHook::BODY_END,
                fn (): string => view('partials.game-analytics-client', ['analyticsContext' => 'filament'])->render(),
            )
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

    protected function resolveBrandName(): string
    {
        return $this->getSettings()?->site_name ?: config('app.name');
    }

    protected function resolveBrandLogoUrl(): ?string
    {
        return $this->resolveMediaUrl($this->getSettings()?->logo_path);
    }

    protected function resolveFaviconUrl(): ?string
    {
        return $this->resolveMediaUrl($this->getSettings()?->favicon_path);
    }

    protected function getSettings(): ?Setting
    {
        if ($this->hasResolvedSettings) {
            return $this->cachedSettings;
        }

        $this->hasResolvedSettings = true;

        try {
            $this->cachedSettings = Setting::query()->first();
        } catch (QueryException) {
            $this->cachedSettings = null;
        }

        return $this->cachedSettings;
    }

    protected function resolveMediaUrl(?string $path): ?string
    {
        if (! is_string($path) || trim($path) === '') {
            return null;
        }

        if (Str::startsWith($path, ['http://', 'https://', '/'])) {
            return $path;
        }

        return Storage::disk('public')->url($path);
    }
}
