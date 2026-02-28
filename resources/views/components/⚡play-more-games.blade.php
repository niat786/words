<?php

use App\Models\Game;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Component;

new class extends Component {
    public function getGamesProperty(): Collection
    {
        $currentRouteName = request()->route()?->getName();

        return Game::query()
            ->where('is_active', true)
            ->orderByDesc('is_default')
            ->orderBy('title')
            ->get()
            ->map(function (Game $game): array {
                return [
                    'game_key' => $game->game_key,
                    'name' => $this->resolveGameName($game->game_key),
                    'description' => Str::limit(strip_tags((string) ($game->translated('meta_description') ?? $game->meta_description)), 120),
                    'icon_url' => $this->resolveIconUrl($game->icon_path),
                    'url' => $this->resolveGameUrl($game->game_key),
                ];
            })
            ->filter(function (array $game) use ($currentRouteName): bool {
                if ($game['url'] === null) {
                    return false;
                }

                return ! in_array($currentRouteName, $this->routeNamesForGameKey($game['game_key']), true);
            })
            ->values();
    }

    /**
     * @return list<string>
     */
    protected function routeNamesForGameKey(string $gameKey): array
    {
        return match ($gameKey) {
            'wordle' => ['home', 'wordle'],
            'spellbee' => ['spell-bee'],
            default => [],
        };
    }

    protected function resolveGameUrl(string $gameKey): ?string
    {
        return match ($gameKey) {
            'wordle' => route('wordle'),
            'spellbee' => route('spell-bee'),
            default => null,
        };
    }

    protected function resolveGameName(string $gameKey): string
    {
        return match ($gameKey) {
            'wordle' => 'Wordle',
            'spellbee' => 'SpellBee',
            default => Str::headline($gameKey),
        };
    }

    protected function resolveIconUrl(?string $iconPath): ?string
    {
        $path = trim((string) $iconPath);

        if ($path === '') {
            return null;
        }

        if (Str::startsWith($path, ['http://', 'https://', '/'])) {
            return $path;
        }

        return Storage::disk('public')->url($path);
    }
};
?>

<section class="mt-2">
    <div class="text-center mb-12">
        <div
            class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-green-500/10 text-green-600 dark:text-green-400 text-[11px] font-bold uppercase tracking-wider mb-4">
            <i class="fa-solid fa-gamepad"></i>
            More Puzzles
        </div>
        <h2 class="text-4xl md:text-5xl font-black tracking-tight text-slate-900 dark:text-white mb-4">
            Play Other Games
        </h2>
        <p class="text-slate-600 dark:text-slate-400 text-lg max-w-2xl mx-auto">
            Explore our collection of mind-bending word puzzles and brain teasers
        </p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse ($this->games as $game)
            <a
                href="{{ $game['url'] }}"
                wire:key="game-card-{{ $game['game_key'] }}"
                data-game-track="{{ $game['game_key'] }}"
                data-game-track-event="open_game"
                class="group relative overflow-hidden rounded-3xl bg-white dark:bg-white/5 border border-slate-200/60 dark:border-white/5 hover:border-green-500/50 transition-all duration-300 hover:shadow-2xl hover:shadow-green-500/10 hover:-translate-y-2"
            >
                <div
                    class="absolute top-0 right-0 w-32 h-32 bg-green-500/10 rounded-full blur-3xl group-hover:blur-2xl transition-all">
                </div>
                <div class="relative p-6">
                    <div class="flex items-start justify-between mb-4">
                        <div
                            class="w-14 h-14 bg-linear-to-br from-green-500 to-emerald-600 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-green-500/30 group-hover:scale-110 group-hover:rotate-6 transition-all">
                            @if ($game['icon_url'] !== null)
                                <img src="{{ $game['icon_url'] }}" alt="{{ $game['name'] }} icon" class="h-10 w-10 rounded-lg object-cover">
                            @else
                                <i class="fa-solid fa-gamepad text-xl"></i>
                            @endif
                        </div>
                        <span
                            class="px-3 py-1 bg-green-500/10 text-green-600 dark:text-green-400 text-xs font-bold rounded-full">Play</span>
                    </div>
                    <h3 class="text-2xl font-black text-slate-900 dark:text-white mb-2">{{ $game['name'] }}</h3>
                    <p class="text-slate-600 dark:text-slate-400 text-sm leading-relaxed mb-4">
                        {{ $game['description'] !== '' ? $game['description'] : 'Play this game now and challenge your vocabulary.' }}
                    </p>
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-semibold text-slate-500 dark:text-slate-400">Open game</span>
                        <i class="fa-solid fa-arrow-right text-green-600 dark:text-green-400 group-hover:translate-x-1 transition-transform"></i>
                    </div>
                </div>
            </a>
        @empty
            <div class="col-span-full rounded-3xl border border-dashed border-slate-300 bg-white/60 px-6 py-10 text-center text-sm font-medium text-slate-600 dark:border-white/15 dark:bg-white/5 dark:text-slate-300">
                No additional games are available yet.
            </div>
        @endforelse
    </div>
</section>
