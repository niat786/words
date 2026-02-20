<?php

use App\Filament\Widgets\GamesSummaryWidget;
use App\Providers\Filament\AdminPanelProvider;
use App\Filament\Widgets\RecentGameEventsWidget;
use App\Models\Game;
use Filament\Panel;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;

it('redirects guests away from admin dashboard', function (): void {
    $this->get('/admin')->assertRedirect('/admin/login');
});

it('registers recent game events widget instead of filament info widget', function (): void {
    $provider = new AdminPanelProvider(app());
    $panel = $provider->panel(app(Panel::class));
    $widgets = $panel->getWidgets();

    expect($widgets)
        ->toContain(AccountWidget::class)
        ->toContain(GamesSummaryWidget::class)
        ->toContain(RecentGameEventsWidget::class)
        ->not->toContain(FilamentInfoWidget::class);
});

it('shows games summary totals', function (): void {
    Game::factory()->create([
        'title' => 'Wordle',
        'is_active' => true,
        'is_default' => true,
    ]);
    Game::factory()->create([
        'title' => 'SpellBee: Spelling Bee Game',
        'is_active' => true,
        'is_default' => false,
    ]);
    Game::factory()->create([
        'title' => 'Memory',
        'is_active' => false,
        'is_default' => false,
    ]);

    $widget = app(GamesSummaryWidget::class);
    expect($widget->getTotalGames())->toBe(3);
});
