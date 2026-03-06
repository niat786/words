<?php

use App\Models\Game;
use Database\Seeders\GameSeeder;

beforeEach(function (): void {
    $this->seed(GameSeeder::class);
});

it('seeds wordle game with default icon and sample data', function (): void {
    $wordle = Game::query()->where('game_key', 'wordle')->first();

    expect($wordle)->not->toBeNull();
    expect($wordle->icon_path)->toBe('/images/wordle-game-logo.webp');
    expect($wordle->title)->toBe('Wordle');
    expect($wordle->meta_description)->toContain('Wordle');
    expect($wordle->is_active)->toBeTrue();
    expect($wordle->is_default)->toBeTrue();
});

it('seeds spellbee game with default icon and sample data', function (): void {
    $spellbee = Game::query()->where('game_key', 'spellbee')->first();

    expect($spellbee)->not->toBeNull();
    expect($spellbee->icon_path)->toBe('/images/spell-bee-game-logo.webp');
    expect($spellbee->title)->toBe('SpellBee');
    expect($spellbee->meta_description)->toContain('SpellBee');
    expect($spellbee->is_active)->toBeTrue();
    expect($spellbee->is_default)->toBeFalse();
});

it('can be run multiple times without duplicating games', function (): void {
    $this->seed(GameSeeder::class);

    expect(Game::query()->where('game_key', 'wordle')->count())->toBe(1);
    expect(Game::query()->where('game_key', 'spellbee')->count())->toBe(1);
});
