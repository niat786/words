<?php

use App\Models\Game;

it('loads seo fields from the configured default game on home page', function (): void {
    Game::query()->create([
        'game_key' => 'spellbee',
        'title' => 'SpellBee Arena Home',
        'content' => '<p>SpellBee content</p>',
        'meta_description' => 'SpellBee home SEO description.',
        'ads_schema_markup' => '{"@context":"https://schema.org","@type":"WebPage","name":"SpellBee Arena Home"}',
        'focus_keyword' => 'spellbee arena',
        'canonical_url' => 'https://example.com/spellbee-home',
        'robots_index' => true,
        'robots_follow' => true,
        'og_title' => 'OG SpellBee Arena',
        'og_description' => 'OG spellbee description',
        'twitter_title' => 'Twitter SpellBee Arena',
        'twitter_description' => 'Twitter spellbee description',
        'is_active' => true,
        'is_default' => true,
    ]);

    $response = $this->get(route('home'));

    $response->assertOk();
    $response->assertSee('<title>SpellBee Arena Home</title>', false);
    $response->assertSee('name="description" content="SpellBee home SEO description."', false);
    $response->assertSee('name="keywords" content="spellbee arena"', false);
    $response->assertSee('rel="canonical" href="https://example.com/spellbee-home"', false);
    $response->assertSee('property="og:title" content="OG SpellBee Arena"', false);
    $response->assertSee('name="twitter:title" content="Twitter SpellBee Arena"', false);
    $response->assertSee('application/ld+json', false);
    $response->assertSee('"@type":"WebPage"', false);
    $response->assertSee('SpellBee');
    $response->assertSee('id="spellbee-hive"', false);
    $response->assertSee('SpellBee content');
});

it('falls back to wordle game seo when no default game is set', function (): void {
    Game::query()->create([
        'game_key' => 'wordle',
        'title' => 'Wordle SEO Home',
        'content' => '<p>Wordle content</p>',
        'meta_description' => 'Wordle fallback SEO description.',
        'ads_schema_markup' => null,
        'focus_keyword' => 'wordle puzzle',
        'canonical_url' => 'https://example.com/wordle-home',
        'robots_index' => true,
        'robots_follow' => true,
        'og_title' => 'OG Wordle Home',
        'og_description' => 'OG wordle description',
        'twitter_title' => 'Twitter Wordle Home',
        'twitter_description' => 'Twitter wordle description',
        'is_active' => true,
        'is_default' => false,
    ]);

    Game::query()->create([
        'game_key' => 'tiles',
        'title' => 'Tiles Home',
        'content' => '<p>Tiles content</p>',
        'meta_description' => 'Tiles description',
        'ads_schema_markup' => null,
        'focus_keyword' => 'tiles game',
        'canonical_url' => 'https://example.com/tiles-home',
        'robots_index' => true,
        'robots_follow' => true,
        'og_title' => 'OG Tiles',
        'og_description' => 'OG tiles description',
        'twitter_title' => 'Twitter Tiles',
        'twitter_description' => 'Twitter tiles description',
        'is_active' => true,
        'is_default' => false,
    ]);

    $response = $this->get(route('home'));

    $response->assertOk();
    $response->assertSee('<title>Wordle SEO Home</title>', false);
    $response->assertSee('name="description" content="Wordle fallback SEO description."', false);
    $response->assertSee('rel="canonical" href="https://example.com/wordle-home"', false);
    $response->assertDontSee('<title>Tiles Home</title>', false);
    $response->assertSee('Wordle content');
});
