<?php

namespace App\Filament\Resources\Games\Pages;

use App\Filament\Resources\Games\GameResource;
use App\Models\Game;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Validation\ValidationException;

class CreateGame extends CreateRecord
{
    protected static string $resource = GameResource::class;

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $gameKey = $data['game_key'] ?? null;

        if (! is_string($gameKey) || $gameKey === '') {
            throw ValidationException::withMessages([
                'data.game_key' => 'Please select a game.',
            ]);
        }

        $alreadyExists = Game::query()
            ->where('game_key', $gameKey)
            ->exists();

        if ($alreadyExists) {
            throw ValidationException::withMessages([
                'data.game_key' => 'This game already exists. Please edit it instead.',
            ]);
        }

        if (Game::query()->count() === 0) {
            $data['is_default'] = true;
        }

        if (($data['is_default'] ?? false) === true) {
            $data['is_active'] = true;
        }

        return $data;
    }

    protected function afterCreate(): void
    {
        if ($this->record->is_default) {
            Game::query()
                ->whereKeyNot($this->record->getKey())
                ->update(['is_default' => false]);
        }
    }
}
