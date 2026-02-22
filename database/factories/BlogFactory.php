<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Blog>
 */
class BlogFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->sentence(6);
        $slug = Str::slug($title).'-'.fake()->unique()->randomNumber(4);

        return [
            'user_id' => User::factory(),
            'title' => $title,
            'title_translations' => [
                'en_US' => $title,
                'en_GB' => $title,
                'es_ES' => $title.' ES',
            ],
            'slug' => $slug,
            'content' => '<h2>'.fake()->sentence(4).'</h2><p>'.fake()->paragraph(8).'</p><p>'.fake()->paragraph(8).'</p>',
            'content_translations' => [
                'en_US' => '<h2>'.fake()->sentence(4).'</h2><p>'.fake()->paragraph(8).'</p>',
                'en_GB' => '<h2>'.fake()->sentence(4).'</h2><p>'.fake()->paragraph(8).'</p>',
                'es_ES' => '<h2>'.fake()->sentence(4).'</h2><p>'.fake()->paragraph(8).'</p>',
            ],
            'excerpt' => fake()->sentence(18),
            'excerpt_translations' => [
                'en_US' => fake()->sentence(18),
                'en_GB' => fake()->sentence(18),
                'es_ES' => fake()->sentence(18),
            ],
            'featured_image_path' => null,
            'featured_image_alt' => null,
            'status' => 'draft',
            'is_featured' => false,
            'published_at' => null,
            'views_count' => 0,
            'reading_time_minutes' => fake()->numberBetween(2, 12),
            'seo_title' => $title,
            'meta_description' => fake()->sentence(20),
            'focus_keyword' => fake()->word(),
            'canonical_url' => null,
            'robots_index' => true,
            'robots_follow' => true,
            'og_title' => null,
            'og_description' => null,
            'twitter_title' => null,
            'twitter_description' => null,
            'schema_type' => 'BlogPosting',
            'schema_markup_json' => null,
            'seo_score' => null,
            'seo_grade' => null,
        ];
    }

    public function published(): static
    {
        return $this->state(fn (): array => [
            'status' => 'published',
            'published_at' => now()->subHour(),
        ]);
    }

    public function scheduled(): static
    {
        return $this->state(fn (): array => [
            'status' => 'scheduled',
            'published_at' => now()->subHour(),
        ]);
    }
}
