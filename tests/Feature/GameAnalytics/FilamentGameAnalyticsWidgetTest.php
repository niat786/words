<?php

use App\Filament\Widgets\GameAnalyticsChart;
use App\Filament\Widgets\GameAnalyticsOverview;
use App\Models\GameAnalytics;
use App\Models\User;

it('builds expected stats for wordle and spellbee overview widget', function (): void {
    $user = User::factory()->create();

    GameAnalytics::factory()->for($user)->createMany([
        [
            'game_key' => 'wordle',
            'event_type' => 'game_completed',
            'status' => 'won',
            'created_at' => now()->subDays(1),
        ],
        [
            'game_key' => 'wordle',
            'event_type' => 'game_completed',
            'status' => 'lost',
            'created_at' => now()->subDays(1),
        ],
        [
            'game_key' => 'spellbee',
            'event_type' => 'open_game',
            'score' => 120,
            'created_at' => now(),
        ],
    ]);

    $widget = app(GameAnalyticsOverview::class);
    $method = new ReflectionMethod($widget, 'getStats');
    $method->setAccessible(true);
    $stats = $method->invoke($widget);

    expect($stats)->toHaveCount(4);
    expect($stats[0]->getValue())->toBe('3');
    expect($stats[1]->getValue())->toBe('1');
    expect($stats[2]->getValue())->toBe('50%');
    expect($stats[3]->getValue())->toBe('1');
});

it('builds chart datasets grouped by game key', function (): void {
    $user = User::factory()->create();

    GameAnalytics::factory()->for($user)->createMany([
        [
            'game_key' => 'wordle',
            'event_type' => 'game_completed',
            'created_at' => now()->subDays(2),
        ],
        [
            'game_key' => 'wordle',
            'event_type' => 'game_completed',
            'created_at' => now()->subDays(1),
        ],
        [
            'game_key' => 'spellbee',
            'event_type' => 'open_game',
            'created_at' => now()->subDays(1),
        ],
    ]);

    $widget = app(GameAnalyticsChart::class);
    $widget->filter = '7';

    $method = new ReflectionMethod($widget, 'getData');
    $method->setAccessible(true);

    /** @var array{datasets: array<int, array{label: string, data: array<int, int>}>, labels: array<int, string>} $data */
    $data = $method->invoke($widget);

    expect($data['labels'])->toHaveCount(7);
    expect($data['datasets'])->not->toBeEmpty();
    expect(collect($data['datasets'])->pluck('label')->all())->toContain('Wordle', 'Spellbee');
});
