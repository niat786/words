<?php

namespace App\Filament\Resources\Games\Pages;

use App\Filament\Resources\Games\GameResource;
use App\Models\Game;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Validation\ValidationException;

class EditGame extends EditRecord
{
    protected static string $resource = GameResource::class;

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     * @throws ValidationException
     */
    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (($data['is_default'] ?? false) === true) {
            $data['is_active'] = true;

            return $data;
        }

        $hasAnotherDefaultGame = Game::query()
            ->whereKeyNot($this->record->getKey())
            ->where('is_default', true)
            ->exists();

        if (! $hasAnotherDefaultGame) {
            throw ValidationException::withMessages([
                'data.is_default' => 'Please keep at least one game as default.',
            ]);
        }

        return $data;
    }

    protected function afterSave(): void
    {
        if ($this->record->is_default) {
            Game::query()
                ->whereKeyNot($this->record->getKey())
                ->update(['is_default' => false]);
        }
    }

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
