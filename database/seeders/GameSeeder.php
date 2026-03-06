<?php

namespace Database\Seeders;

use App\Models\Game;
use App\Support\Localization\SupportedLocales;
use Illuminate\Database\Seeder;

class GameSeeder extends Seeder
{
    /**
     * Default game icons from public/images (used when no user-uploaded icon).
     */
    protected const DEFAULT_ICONS = [
        'wordle' => '/images/wordle-game-logo.webp',
        'spellbee' => '/images/spell-bee-game-logo.webp',
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->seedWordle();
        $this->seedSpellBee();
    }

    protected function seedWordle(): void
    {
        $title = 'Wordle';
        $content = '<p>Guess the hidden word in six tries. Each guess must be a valid five-letter word. Hit the enter button to submit. After each guess, the color of the tiles will change to show how close your guess was to the word.</p>';
        $metaDescription = 'Play Wordle - the daily word puzzle game. Guess the 5-letter word in six tries. Free to play online.';

        Game::query()->updateOrCreate(
            ['game_key' => 'wordle'],
            [
                'icon_path' => self::DEFAULT_ICONS['wordle'],
                'title' => $title,
                'title_translations' => $this->translationsFor($title),
                'content' => $content,
                'content_translations' => $this->translationsFor($content),
                'meta_description' => $metaDescription,
                'meta_description_translations' => $this->translationsFor($metaDescription),
                'focus_keyword' => 'wordle',
                'canonical_url' => null,
                'robots_index' => true,
                'robots_follow' => true,
                'og_title' => $title,
                'og_description' => $metaDescription,
                'twitter_title' => $title,
                'twitter_description' => $metaDescription,
                'is_active' => true,
                'is_default' => true,
            ]
        );
    }

    protected function seedSpellBee(): void
    {
        $title = 'SpellBee';
        $content = '<p>SpellBee is a spelling bee game where you form words from a set of letters. Create as many words as you can using the center letter at least once. Longer words score more points.</p>';
        $metaDescription = 'Play SpellBee - the spelling bee word game. Form words from letters and score points. Free to play online.';

        Game::query()->updateOrCreate(
            ['game_key' => 'spellbee'],
            [
                'icon_path' => self::DEFAULT_ICONS['spellbee'],
                'title' => $title,
                'title_translations' => $this->translationsFor($title),
                'content' => $content,
                'content_translations' => $this->translationsFor($content),
                'meta_description' => $metaDescription,
                'meta_description_translations' => $this->translationsFor($metaDescription),
                'focus_keyword' => 'spell bee',
                'canonical_url' => null,
                'robots_index' => true,
                'robots_follow' => true,
                'og_title' => $title,
                'og_description' => $metaDescription,
                'twitter_title' => $title,
                'twitter_description' => $metaDescription,
                'is_active' => true,
                'is_default' => false,
            ]
        );
    }

    /**
     * @return array<string, string>
     */
    protected function translationsFor(string $value): array
    {
        $translations = [];

        foreach (array_keys(SupportedLocales::all()) as $locale) {
            $translations[$locale] = $value;
        }

        return $translations;
    }
}
