<?php

use App\Models\Setting;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

test('guests are redirected to the login page', function () {
    $response = $this->get(route('dashboard'));
    $response->assertRedirect(route('login'));
});

test('authenticated users can visit the dashboard', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->get(route('dashboard'));
    $response
        ->assertOk()
        ->assertSee('Welcome to your dashboard')
        ->assertSee('fresh Filament setup');
});

test('dashboard header uses configured site logo and name', function () {
    $logoPath = 'site/branding/dashboard-logo.png';
    $logoUrl = Storage::disk('public')->url($logoPath);

    Setting::query()->create([
        'default_game' => 'wordle',
        'site_name' => 'Wordly Studio',
        'logo_path' => $logoPath,
    ]);

    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->get(route('dashboard'));

    $response
        ->assertOk()
        ->assertSee("src=\"{$logoUrl}\"", false)
        ->assertSee('Wordly Studio');
});
