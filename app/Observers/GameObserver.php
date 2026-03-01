<?php

namespace App\Observers;

use App\Models\Game;
use Illuminate\Support\Facades\Storage;

class GameObserver
{
    public function updated(Game $game): void
    {
        if (! $game->wasChanged('icon_path')) {
            return;
        }

        $originalIconPath = Game::normalizeIconStoragePath((string) $game->getOriginal('icon_path'));

        if ($originalIconPath === null) {
            return;
        }

        $newIconPath = Game::normalizeIconStoragePath($game->icon_path);

        if ($newIconPath === $originalIconPath) {
            return;
        }

        Storage::disk('public')->delete($originalIconPath);
    }

    public function deleted(Game $game): void
    {
        $iconPath = Game::normalizeIconStoragePath($game->icon_path);

        if ($iconPath === null) {
            return;
        }

        Storage::disk('public')->delete($iconPath);
    }
}
