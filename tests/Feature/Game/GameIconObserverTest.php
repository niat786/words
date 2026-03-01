<?php

use App\Models\Game;
use Illuminate\Support\Facades\Storage;

it('deletes previous icon when a game icon is replaced', function (): void {
    Storage::fake('public');

    Storage::disk('public')->put('games/icons/old.png', 'old');
    Storage::disk('public')->put('games/icons/new.png', 'new');

    $game = Game::factory()->create([
        'icon_path' => 'games/icons/old.png',
    ]);

    $game->update([
        'icon_path' => 'games/icons/new.png',
    ]);

    Storage::disk('public')->assertMissing('games/icons/old.png');
    Storage::disk('public')->assertExists('games/icons/new.png');
});

it('deletes icon file when a game is deleted', function (): void {
    Storage::fake('public');

    Storage::disk('public')->put('games/icons/delete-me.png', 'icon');

    $game = Game::factory()->create([
        'icon_path' => 'games/icons/delete-me.png',
    ]);

    $game->delete();

    Storage::disk('public')->assertMissing('games/icons/delete-me.png');
});
