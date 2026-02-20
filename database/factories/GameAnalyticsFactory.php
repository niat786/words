<?php

namespace Database\Factories;

use App\Models\Game;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\GameAnalytics>
 */
class GameAnalyticsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'game_id' => null,
            'game_key' => fake()->randomElement(['wordle', 'spellbee']),
            'client_event_id' => (string) fake()->uuid(),
            'event_type' => fake()->randomElement(['game_started', 'game_completed', 'open_game']),
            'status' => fake()->randomElement(['won', 'lost', null]),
            'attempts' => fake()->numberBetween(1, 6),
            'word_length' => fake()->numberBetween(4, 11),
            'score' => fake()->numberBetween(0, 300),
            'duration_seconds' => fake()->numberBetween(5, 900),
            'occurred_at' => now(),
            'metadata' => ['source' => 'factory'],
        ];
    }

    public function forGame(Game $game): static
    {
        return $this->state(fn (): array => [
            'game_id' => $game->id,
            'game_key' => $game->game_key,
        ]);
    }
}
