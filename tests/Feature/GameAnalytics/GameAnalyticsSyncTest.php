<?php

use App\Models\Game;
use App\Models\User;
use Illuminate\Support\Str;

it('stores a single analytics event for an authenticated user', function (): void {
    $user = User::factory()->create();
    $game = Game::factory()->create([
        'game_key' => 'wordle',
    ]);

    $payload = [
        'client_event_id' => (string) Str::uuid(),
        'game_key' => 'wordle',
        'event_type' => 'game_completed',
        'status' => 'won',
        'attempts' => 3,
        'word_length' => 5,
        'duration_seconds' => 45,
        'metadata' => ['source' => 'test'],
    ];

    $this->actingAs($user)
        ->postJson(route('game-analytics.store'), $payload)
        ->assertOk()
        ->assertJson(['saved' => true]);

    $this->assertDatabaseHas('game_analytics', [
        'user_id' => $user->id,
        'game_id' => $game->id,
        'client_event_id' => $payload['client_event_id'],
        'game_key' => 'wordle',
        'event_type' => 'game_completed',
        'status' => 'won',
        'attempts' => 3,
        'word_length' => 5,
    ]);
});

it('syncs queued analytics events and avoids duplicates by client event id', function (): void {
    $user = User::factory()->create();
    Game::factory()->create([
        'game_key' => 'wordle',
    ]);
    Game::factory()->create([
        'game_key' => 'spellbee',
    ]);

    $duplicateEventId = (string) Str::uuid();

    $events = [
        [
            'client_event_id' => $duplicateEventId,
            'game_key' => 'wordle',
            'event_type' => 'game_started',
            'word_length' => 5,
        ],
        [
            'client_event_id' => $duplicateEventId,
            'game_key' => 'wordle',
            'event_type' => 'game_completed',
            'status' => 'won',
            'attempts' => 4,
        ],
        [
            'client_event_id' => (string) Str::uuid(),
            'game_key' => 'spellbee',
            'event_type' => 'open_game',
        ],
    ];

    $this->actingAs($user)
        ->postJson(route('game-analytics.sync'), ['events' => $events])
        ->assertOk()
        ->assertJson(['saved' => 3]);

    $this->assertDatabaseCount('game_analytics', 2);
    $this->assertDatabaseHas('game_analytics', [
        'user_id' => $user->id,
        'client_event_id' => $duplicateEventId,
        'event_type' => 'game_completed',
        'status' => 'won',
        'attempts' => 4,
    ]);
    $this->assertDatabaseHas('game_analytics', [
        'user_id' => $user->id,
        'game_key' => 'spellbee',
        'event_type' => 'open_game',
    ]);
});

it('rejects analytics ingestion for guests', function (): void {
    $this->postJson(route('game-analytics.store'), [
        'client_event_id' => (string) Str::uuid(),
        'game_key' => 'wordle',
        'event_type' => 'game_started',
    ])->assertUnauthorized();
});
