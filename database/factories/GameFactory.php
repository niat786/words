<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

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
            'icon_path' => null,
            'title' => $title,
            'title_translations' => [
                'en_US' => $title,
                'en_GB' => $title,
                'es_ES' => $title.' ES',
            ],
            'content' => '<p>'.fake()->sentence(16).'</p>',
            'content_translations' => [
                'en_US' => '<p>'.fake()->sentence(16).'</p>',
                'en_GB' => '<p>'.fake()->sentence(16).'</p>',
                'es_ES' => '<p>'.fake()->sentence(16).'</p>',
            ],
            'meta_description' => fake()->sentence(18),
            'meta_description_translations' => [
                'en_US' => fake()->sentence(18),
                'en_GB' => fake()->sentence(18),
                'es_ES' => fake()->sentence(18),
            ],
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
