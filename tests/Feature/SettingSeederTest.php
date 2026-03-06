<?php

use App\Models\Setting;
use Database\Seeders\SettingSeeder;

beforeEach(function (): void {
    $this->seed(SettingSeeder::class);
});

it('seeds site settings with default logo path', function (): void {
    $settings = Setting::query()->first();

    expect($settings)->not->toBeNull();
    expect($settings->logo_path)->toBe('/images/site-logo-default.webp');
    expect($settings->default_game)->toBe('wordle');
    expect($settings->enabled_games)->toBe(['wordle', 'spellbee']);
});

it('creates settings when none exist', function (): void {
    Setting::query()->delete();

    $this->seed(SettingSeeder::class);

    expect(Setting::query()->count())->toBe(1);
    expect(Setting::query()->first()?->logo_path)->toBe('/images/site-logo-default.webp');
});

it('updates existing settings without duplicating', function (): void {
    $settings = Setting::query()->first();
    $settings->update(['logo_path' => 'site/branding/custom.png']);

    $this->seed(SettingSeeder::class);

    expect(Setting::query()->count())->toBe(1);
    expect(Setting::query()->first()?->logo_path)->toBe('/images/site-logo-default.webp');
});
