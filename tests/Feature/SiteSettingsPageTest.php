<?php

use App\Filament\Pages\SiteSettings;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;

it('redirects guests away from site settings page', function (): void {
    $this->get('/admin/site-settings')
        ->assertRedirect('/admin/login');
});

it('allows authenticated users to save site settings', function (): void {
    $user = User::factory()->create();
    $this->actingAs($user);

    Livewire::test(SiteSettings::class)
        ->set('data.site_name', 'Wordly Studio')
        ->set('data.site_tagline', 'Play smarter every day')
        ->set('data.site_description', 'A dynamic footer description.')
        ->set('data.global_header_code', '<meta name="verify" content="abc123">')
        ->set('data.facebook_url', 'https://facebook.com/wordly')
        ->set('data.instagram_url', 'https://instagram.com/wordly')
        ->set('data.youtube_url', 'https://youtube.com/@wordly')
        ->set('data.x_url', 'https://x.com/wordly')
        ->set('data.pinterest_url', 'https://pinterest.com/wordly')
        ->call('save');

    $settings = Setting::query()->first();

    expect($settings)->not->toBeNull();
    expect($settings?->site_name)->toBe('Wordly Studio');
    expect($settings?->site_tagline)->toBe('Play smarter every day');
    expect($settings?->site_description)->toBe('A dynamic footer description.');
    expect($settings?->global_header_code)->toBe('<meta name="verify" content="abc123">');
    expect($settings?->facebook_url)->toBe('https://facebook.com/wordly');
    expect($settings?->instagram_url)->toBe('https://instagram.com/wordly');
    expect($settings?->youtube_url)->toBe('https://youtube.com/@wordly');
    expect($settings?->x_url)->toBe('https://x.com/wordly');
    expect($settings?->pinterest_url)->toBe('https://pinterest.com/wordly');
});

it('uses the configured site favicon in frontend head output', function (): void {
    $faviconPath = 'site/branding/favicon.png';
    $appleTouchPath = 'site/branding/apple-touch.png';

    Setting::query()->create([
        'default_game' => 'wordle',
        'favicon_path' => $faviconPath,
        'apple_touch_icon_path' => $appleTouchPath,
    ]);

    $response = $this->get(route('home'));
    $faviconUrl = Storage::disk('public')->url($faviconPath);
    $appleTouchUrl = Storage::disk('public')->url($appleTouchPath);

    $response->assertOk();
    $response->assertSee("rel=\"icon\" href=\"{$faviconUrl}\"", false);
    $response->assertSee("rel=\"shortcut icon\" href=\"{$faviconUrl}\"", false);
    $response->assertSee("rel=\"apple-touch-icon\" href=\"{$appleTouchUrl}\"", false);
});

it('shows configured logo and site branding across game pages', function (): void {
    $logoPath = 'site/branding/logo.png';
    $logoUrl = Storage::disk('public')->url($logoPath);

    Setting::query()->create([
        'default_game' => 'wordle',
        'site_name' => 'Wordly Studio',
        'site_tagline' => 'Play Daily',
        'site_description' => 'Footer description from settings.',
        'logo_path' => $logoPath,
        'facebook_url' => 'https://facebook.com/wordly',
        'x_url' => 'https://x.com/wordly',
    ]);

    $home = $this->get(route('home'));
    $home->assertOk();
    $home->assertSee("src=\"{$logoUrl}\"", false);
    $home->assertSee('Wordly Studio');
    $home->assertSee('Play Daily');
    $home->assertSee('Footer description from settings.');
    $home->assertSee('https://facebook.com/wordly');
    $home->assertSee('https://x.com/wordly');

    $spellBee = $this->get(route('spell-bee'));
    $spellBee->assertOk();
    $spellBee->assertSee("src=\"{$logoUrl}\"", false);
    $spellBee->assertSee('Wordly Studio');
    $spellBee->assertSee('Play Daily');
});

it('deletes replaced branding files from public storage', function (): void {
    Storage::fake('public');

    $user = User::factory()->create();
    $this->actingAs($user);

    Storage::disk('public')->put('site/branding/logo-old.png', 'old');
    Storage::disk('public')->put('site/branding/logo-new.png', 'new');

    Setting::query()->create([
        'default_game' => 'wordle',
        'logo_path' => 'site/branding/logo-old.png',
    ]);

    Livewire::test(SiteSettings::class)
        ->set('data.logo_path', ['site/branding/logo-new.png'])
        ->call('save');

    Storage::disk('public')->assertMissing('site/branding/logo-old.png');
    Storage::disk('public')->assertExists('site/branding/logo-new.png');
});
