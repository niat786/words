<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreGameAnalyticsRequest;
use App\Http\Requests\SyncGameAnalyticsRequest;
use App\Models\Game;
use App\Models\GameAnalytics;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;

class GameAnalyticsController extends Controller
{
    public function store(StoreGameAnalyticsRequest $request): JsonResponse
    {
        $event = $request->validated();
        $gameIdByKey = $this->resolveGameIds(collect([$event]));

        $record = $this->persistEvent(
            userId: (int) $request->user()->getAuthIdentifier(),
            event: $event,
            gameIdByKey: $gameIdByKey,
        );

        return response()->json([
            'saved' => true,
            'id' => $record->id,
        ]);
    }

    public function sync(SyncGameAnalyticsRequest $request): JsonResponse
    {
        /** @var list<array<string, mixed>> $events */
        $events = $request->validated('events');
        $gameIdByKey = $this->resolveGameIds(collect($events));
        $created = 0;

        foreach ($events as $event) {
            $record = $this->persistEvent(
                userId: (int) $request->user()->getAuthIdentifier(),
                event: $event,
                gameIdByKey: $gameIdByKey,
            );

            if ($record->wasRecentlyCreated) {
                $created++;
            }
        }

        return response()->json([
            'saved' => count($events),
            'created' => $created,
            'updated' => count($events) - $created,
        ]);
    }

    /**
     * @param  array<string, mixed>  $event
     * @param  array<string, int>  $gameIdByKey
     */
    protected function persistEvent(int $userId, array $event, array $gameIdByKey): GameAnalytics
    {
        return GameAnalytics::query()->updateOrCreate(
            [
                'user_id' => $userId,
                'client_event_id' => (string) $event['client_event_id'],
            ],
            [
                'game_id' => $gameIdByKey[(string) $event['game_key']] ?? null,
                'game_key' => (string) $event['game_key'],
                'event_type' => (string) $event['event_type'],
                'status' => $event['status'] ?? null,
                'attempts' => $event['attempts'] ?? null,
                'word_length' => $event['word_length'] ?? null,
                'score' => $event['score'] ?? null,
                'duration_seconds' => $event['duration_seconds'] ?? null,
                'occurred_at' => $event['occurred_at'] ?? now(),
                'metadata' => $event['metadata'] ?? null,
            ],
        );
    }

    /**
     * @param  Collection<int, array<string, mixed>>  $events
     * @return array<string, int>
     */
    protected function resolveGameIds(Collection $events): array
    {
        /** @var list<string> $gameKeys */
        $gameKeys = $events
            ->pluck('game_key')
            ->filter(fn (mixed $value): bool => is_string($value) && $value !== '')
            ->unique()
            ->values()
            ->all();

        if ($gameKeys === []) {
            return [];
        }

        /** @var array<string, int> $gameIdByKey */
        $gameIdByKey = Game::query()
            ->whereIn('game_key', $gameKeys)
            ->pluck('id', 'game_key')
            ->map(fn (mixed $id): int => (int) $id)
            ->all();

        return $gameIdByKey;
    }
}
