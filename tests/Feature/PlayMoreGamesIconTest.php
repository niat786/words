<?php

use App\Models\Game;

it('renders dashboard uploaded game icons in play more games', function () {
    Game::factory()->create([
        'game_key' => 'wordle',
        'title' => 'Wordle',
        'icon_path' => null,
        'is_active' => true,
        'is_default' => true,
    ]);

    Game::factory()->create([
        'game_key' => 'spellbee',
        'title' => 'SpellBee',
        'icon_path' => 'games/icons/spellbee-logo.png',
        'is_active' => true,
    ]);

    $this->get('/wordle')
        ->assertSuccessful()
        ->assertSee('/storage/games/icons/spellbee-logo.png');
});

it('falls back to default icon when uploaded icon is removed', function () {
    Game::factory()->create([
        'game_key' => 'wordle',
        'title' => 'Wordle',
        'icon_path' => null,
        'is_active' => true,
        'is_default' => true,
    ]);

    Game::factory()->create([
        'game_key' => 'spellbee',
        'title' => 'SpellBee',
        'icon_path' => null,
        'is_active' => true,
    ]);

    $this->get('/wordle')
        ->assertSuccessful()
        ->assertSee('fa-solid fa-gamepad');
});
