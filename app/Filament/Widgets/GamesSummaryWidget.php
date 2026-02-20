<?php

namespace App\Filament\Widgets;

use App\Models\Game;
use Filament\Widgets\Widget;

class GamesSummaryWidget extends Widget
{
    protected static ?int $sort = -2;

    protected int | string | array $columnSpan = 1;

    protected static bool $isLazy = false;

    protected string $view = 'filament.widgets.games-summary-widget';

    public function getTotalGames(): int
    {
        return Game::query()->count();
    }
}
