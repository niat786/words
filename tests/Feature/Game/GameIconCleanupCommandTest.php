<?php

use App\Models\Game;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;

it('deletes orphaned game icons while preserving referenced and recent files', function (): void {
    Storage::fake('public');

    Storage::disk('public')->put('games/icons/referenced.png', 'referenced');
    Storage::disk('public')->put('games/icons/orphan-old.png', 'old');
    Storage::disk('public')->put('games/icons/orphan-recent.png', 'recent');

    touch(Storage::disk('public')->path('games/icons/orphan-old.png'), now()->subDays(7)->getTimestamp());
    touch(Storage::disk('public')->path('games/icons/orphan-recent.png'), now()->subHours(3)->getTimestamp());

    Game::factory()->create([
        'icon_path' => 'games/icons/referenced.png',
    ]);

    Artisan::call('games:clean-icons', [
        '--days' => 2,
    ]);

    Storage::disk('public')->assertExists('games/icons/referenced.png');
    Storage::disk('public')->assertExists('games/icons/orphan-recent.png');
    Storage::disk('public')->assertMissing('games/icons/orphan-old.png');
});

it('does not delete files in dry run mode for game icons', function (): void {
    Storage::fake('public');

    Storage::disk('public')->put('games/icons/dry-run-orphan.png', 'orphan');
    touch(Storage::disk('public')->path('games/icons/dry-run-orphan.png'), now()->subDays(4)->getTimestamp());

    Artisan::call('games:clean-icons', [
        '--days' => 0,
        '--dry-run' => true,
    ]);

    Storage::disk('public')->assertExists('games/icons/dry-run-orphan.png');
});
