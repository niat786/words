<?php

use App\Models\Setting;

it('renders settings logo on auth pages', function () {
    Setting::query()->create([
        'logo_path' => 'branding/site-logo.png',
    ]);

    $this->get(route('login'))
        ->assertSuccessful()
        ->assertSee('/storage/branding/site-logo.png');
});
