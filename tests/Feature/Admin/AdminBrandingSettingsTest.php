<?php

use App\Models\Setting;
use App\Providers\Filament\AdminPanelProvider;
use Filament\Panel;
use Illuminate\Support\Facades\Storage;

it('uses site settings for admin brand name, logo, and favicon', function (): void {
    $logoPath = 'site/branding/admin-logo.png';
    $faviconPath = 'site/branding/admin-favicon.png';

    Setting::query()->create([
        'default_game' => 'wordle',
        'site_name' => 'Wordly Studio',
        'logo_path' => $logoPath,
        'favicon_path' => $faviconPath,
    ]);

    $provider = new AdminPanelProvider(app());
    $panel = $provider->panel(app(Panel::class));

    expect(strip_tags((string) $panel->getBrandName()))->toBe('Wordly Studio');
    expect($panel->getBrandLogo())->toBe(Storage::disk('public')->url($logoPath));
    expect($panel->getDarkModeBrandLogo())->toBe(Storage::disk('public')->url($logoPath));
    expect($panel->getFavicon())->toBe(Storage::disk('public')->url($faviconPath));
});
