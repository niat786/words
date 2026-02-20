<?php

namespace App\Filament\Widgets;

use App\Models\GameAnalytics;
use Filament\Support\Enums\IconPosition;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Database\Eloquent\Builder;

class GameAnalyticsOverview extends StatsOverviewWidget
{
    protected ?string $heading = 'Game Analytics';

    protected ?string $description = 'Last 30 days for all tracked games.';

    protected static ?int $sort = -1;

    protected ?string $pollingInterval = '30s';

    protected function getStats(): array
    {
        $startDate = now()->subDays(30)->startOfDay();

        $baseQuery = GameAnalytics::query()
            ->where('created_at', '>=', $startDate);

        $totalEvents = (clone $baseQuery)->count();
        $uniquePlayers = (clone $baseQuery)->distinct('user_id')->count('user_id');

        $wordleCompletionQuery = (clone $baseQuery)
            ->where('game_key', 'wordle')
            ->where('event_type', 'game_completed');
        $wordleCompletions = (clone $wordleCompletionQuery)->count();
        $wordleWins = (clone $wordleCompletionQuery)
            ->where('status', 'won')
            ->count();
        $wordleWinRate = $wordleCompletions > 0
            ? (int) round(($wordleWins / $wordleCompletions) * 100)
            : 0;

        $spellBeeQuery = (clone $baseQuery)
            ->where('game_key', 'spellbee');
        $spellBeeEvents = (clone $spellBeeQuery)->count();
        $spellBeeAverageScore = (int) round(
            (float) ((clone $spellBeeQuery)
                ->whereNotNull('score')
                ->avg('score') ?? 0),
        );

        return [
            Stat::make('Tracked events', number_format($totalEvents))
                ->description('Across all games')
                ->descriptionIcon('heroicon-m-chart-bar-square', IconPosition::Before)
                ->chart($this->dailyCounts((clone $baseQuery), 7))
                ->color('info'),
            Stat::make('Unique players', number_format($uniquePlayers))
                ->description('Authenticated users with events')
                ->descriptionIcon('heroicon-m-users', IconPosition::Before)
                ->color('success'),
            Stat::make('Wordle win rate', "{$wordleWinRate}%")
                ->description(number_format($wordleCompletions) . ' completed rounds')
                ->descriptionIcon('heroicon-m-trophy', IconPosition::Before)
                ->chart($this->dailyCounts((clone $wordleCompletionQuery), 7))
                ->color('warning'),
            Stat::make('SpellBee activity', number_format($spellBeeEvents))
                ->description('Avg score: ' . number_format($spellBeeAverageScore))
                ->descriptionIcon('heroicon-m-bolt', IconPosition::Before)
                ->chart($this->dailyCounts((clone $spellBeeQuery), 7))
                ->color('primary'),
        ];
    }

    /**
     * @return array<float>
     */
    protected function dailyCounts(Builder $query, int $days): array
    {
        $from = now()->subDays($days - 1)->startOfDay();
        $to = now()->endOfDay();

        /** @var array<string, int> $counts */
        $counts = (clone $query)
            ->whereBetween('created_at', [$from, $to])
            ->selectRaw('DATE(created_at) as day, COUNT(*) as aggregate')
            ->groupBy('day')
            ->pluck('aggregate', 'day')
            ->map(fn (mixed $count): int => (int) $count)
            ->all();

        $result = [];

        for ($offset = $days - 1; $offset >= 0; $offset--) {
            $key = now()->subDays($offset)->toDateString();
            $result[] = (float) ($counts[$key] ?? 0);
        }

        return $result;
    }
}
