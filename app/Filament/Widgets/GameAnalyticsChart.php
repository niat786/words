<?php

namespace App\Filament\Widgets;

use App\Models\GameAnalytics;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Collection;

class GameAnalyticsChart extends ChartWidget
{
    protected ?string $heading = 'Game Activity Trend';

    protected ?string $description = 'Daily activity by game key.';

    public ?string $filter = '30';

    protected static ?int $sort = 0;

    protected ?string $pollingInterval = '60s';

    protected function getData(): array
    {
        $days = match ($this->filter) {
            '7' => 7,
            '90' => 90,
            default => 30,
        };

        $startDate = now()->subDays($days - 1)->startOfDay();
        $endDate = now()->endOfDay();

        $baseQuery = GameAnalytics::query()
            ->whereBetween('created_at', [$startDate, $endDate]);

        /** @var list<string> $gameKeys */
        $gameKeys = (clone $baseQuery)
            ->selectRaw('game_key, COUNT(*) as aggregate')
            ->groupBy('game_key')
            ->orderByDesc('aggregate')
            ->limit(6)
            ->pluck('game_key')
            ->all();

        if ($gameKeys === []) {
            $gameKeys = ['wordle', 'spellbee'];
        }

        /** @var Collection<int, object{game_key: string, day: string, aggregate: int}> $rows */
        $rows = (clone $baseQuery)
            ->whereIn('game_key', $gameKeys)
            ->selectRaw('game_key, DATE(created_at) as day, COUNT(*) as aggregate')
            ->groupBy('game_key', 'day')
            ->get();

        $labels = [];
        $dateKeys = [];

        for ($offset = $days - 1; $offset >= 0; $offset--) {
            $date = now()->subDays($offset);
            $dateKeys[] = $date->toDateString();
            $labels[] = $date->format('M j');
        }

        /** @var array<string, array<string, int>> $countsByGame */
        $countsByGame = [];

        foreach ($rows as $row) {
            $countsByGame[$row->game_key][$row->day] = (int) $row->aggregate;
        }

        $palette = [
            ['border' => '#22c55e', 'background' => 'rgba(34, 197, 94, 0.18)'],
            ['border' => '#f59e0b', 'background' => 'rgba(245, 158, 11, 0.18)'],
            ['border' => '#3b82f6', 'background' => 'rgba(59, 130, 246, 0.18)'],
            ['border' => '#ef4444', 'background' => 'rgba(239, 68, 68, 0.18)'],
            ['border' => '#8b5cf6', 'background' => 'rgba(139, 92, 246, 0.18)'],
            ['border' => '#14b8a6', 'background' => 'rgba(20, 184, 166, 0.18)'],
        ];

        $datasets = [];

        foreach ($gameKeys as $index => $gameKey) {
            $colors = $palette[$index % count($palette)];
            $gameCounts = $countsByGame[$gameKey] ?? [];
            $data = [];

            foreach ($dateKeys as $dateKey) {
                $data[] = (int) ($gameCounts[$dateKey] ?? 0);
            }

            $datasets[] = [
                'label' => str($gameKey)->replace('_', ' ')->title()->toString(),
                'data' => $data,
                'borderColor' => $colors['border'],
                'backgroundColor' => $colors['background'],
                'pointRadius' => 3,
                'pointHoverRadius' => 4,
                'fill' => false,
                'tension' => 0.35,
            ];
        }

        return [
            'datasets' => $datasets,
            'labels' => $labels,
        ];
    }

    protected function getFilters(): ?array
    {
        return [
            '7' => 'Last 7 days',
            '30' => 'Last 30 days',
            '90' => 'Last 90 days',
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
