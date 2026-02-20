<?php

namespace App\Filament\Widgets;

use App\Models\GameAnalytics;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class RecentGameEventsWidget extends TableWidget
{
    protected static ?int $sort = 1;

    protected static bool $isLazy = false;

    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(fn (): Builder => GameAnalytics::query()->with('user')->latest())
            ->columns([
                TextColumn::make('created_at')
                    ->label('Time')
                    ->dateTime('M j, Y g:i A')
                    ->sortable(),
                TextColumn::make('game_key')
                    ->label('Game')
                    ->badge()
                    ->sortable()
                    ->searchable(),
                TextColumn::make('event_type')
                    ->label('Event')
                    ->badge()
                    ->sortable()
                    ->searchable(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (?string $state): string => match ($state) {
                        'won' => 'success',
                        'lost' => 'danger',
                        default => 'gray',
                    }),
                TextColumn::make('user.name')
                    ->label('User')
                    ->searchable(),
                TextColumn::make('attempts')
                    ->numeric(),
                TextColumn::make('word_length')
                    ->label('Length')
                    ->numeric(),
                TextColumn::make('score')
                    ->numeric(),
            ])
            ->filters([
                SelectFilter::make('game_key')
                    ->label('Game')
                    ->options(fn (): array => GameAnalytics::query()->distinct()->orderBy('game_key')->pluck('game_key', 'game_key')->all()),
                SelectFilter::make('event_type')
                    ->label('Event')
                    ->options(fn (): array => GameAnalytics::query()->distinct()->orderBy('event_type')->pluck('event_type', 'event_type')->all()),
            ])
            ->headerActions([
                //
            ])
            ->recordActions([
                //
            ])
            ->toolbarActions([])
            ->defaultPaginationPageOption(10);
    }
}
