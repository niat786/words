<?php

use App\Models\Game;

it('keeps seo details in meta tags and not visible body content', function () {
    Game::factory()->create([
        'game_key' => 'spellbee',
        'title' => 'SpellBee',
        'meta_description' => 'Spell bee page description.',
        'canonical_url' => 'https://canonical.com/spellbee',
        'focus_keyword' => 'spell bee',
        'robots_index' => true,
        'robots_follow' => true,
        'is_active' => true,
        'is_default' => false,
    ]);

    $this->get('/spell-bee')
        ->assertSuccessful()
        ->assertSee('<meta name="robots" content="index, follow"', false)
        ->assertSee('<link rel="canonical" href="https://canonical.com/spellbee"', false)
        ->assertDontSee('SEO Details')
        ->assertDontSee('Focus Keyword')
        ->assertDontSee('Canonical URL');
});
