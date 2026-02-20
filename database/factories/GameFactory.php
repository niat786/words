<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Game>
 */
class GameFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $gameKey = Str::slug(fake()->unique()->words(2, true), '_');
        $title = Str::headline($gameKey);

        return [
            'game_key' => $gameKey,
            'title' => $title,
            'content' => '<p>' . fake()->sentence(16) . '</p>',
            'meta_description' => fake()->sentence(18),
            'ads_schema_markup' => null,
            'focus_keyword' => strtolower($title),
            'canonical_url' => fake()->url(),
            'robots_index' => true,
            'robots_follow' => true,
            'og_title' => $title,
            'og_description' => fake()->sentence(14),
            'twitter_title' => $title,
            'twitter_description' => fake()->sentence(14),
            'is_active' => true,
            'is_default' => false,
        ];
    }
}
