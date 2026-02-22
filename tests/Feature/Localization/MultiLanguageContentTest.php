<?php

use App\Models\Blog;
use App\Models\Game;
use App\Models\User;

it('switches locale through the locale route', function () {
    $response = $this->from('/')->get('/locale/es_ES');

    $response->assertRedirect('/');

    expect(session('locale'))->toBe('es_ES');
});

it('renders translated blog content for the active locale', function () {
    $blog = Blog::factory()
        ->published()
        ->create([
            'user_id' => User::factory(),
            'slug' => 'multi-language-blog',
            'title' => 'English Blog Title',
            'title_translations' => [
                'en_US' => 'English Blog Title',
                'es_ES' => 'Titulo del Blog',
            ],
            'excerpt' => 'English excerpt',
            'excerpt_translations' => [
                'en_US' => 'English excerpt',
                'es_ES' => 'Resumen en espanol',
            ],
            'content' => '<p>English blog content</p>',
            'content_translations' => [
                'en_US' => '<p>English blog content</p>',
                'es_ES' => '<p>Contenido en espanol</p>',
            ],
        ]);

    $this->withSession(['locale' => 'es_ES'])
        ->get(route('blog.show', ['slug' => $blog->slug]))
        ->assertSuccessful()
        ->assertSee('Titulo del Blog')
        ->assertSee('Contenido en espanol', false);
});

it('renders translated game metadata for the active locale', function () {
    Game::factory()->create([
        'game_key' => 'wordle',
        'title' => 'Wordle English',
        'title_translations' => [
            'en_US' => 'Wordle English',
            'fr_FR' => 'Wordle Francais',
        ],
        'meta_description' => 'English description',
        'meta_description_translations' => [
            'en_US' => 'English description',
            'fr_FR' => 'Description francaise',
        ],
        'content' => '<p>English game content</p>',
        'content_translations' => [
            'en_US' => '<p>English game content</p>',
            'fr_FR' => '<p>Contenu du jeu</p>',
        ],
        'is_default' => true,
    ]);

    $this->withSession(['locale' => 'fr_FR'])
        ->get('/wordle')
        ->assertSuccessful()
        ->assertSee('Wordle Francais');
});

it('renders localized fixed home page labels', function () {
    $this->withSession(['locale' => 'es_ES'])
        ->get('/wordle')
        ->assertSuccessful()
        ->assertSee('Longitud de palabra')
        ->assertSee('5 letras')
        ->assertSee('Entrar');
});

it('renders fixed home labels for english uk locale', function () {
    $this->withSession(['locale' => 'en_GB'])
        ->get('/wordle')
        ->assertSuccessful()
        ->assertSee('Word Length')
        ->assertSee('5 Letter Words')
        ->assertSee('Enter');
});

it('does not render raw home translation keys for any supported locale', function () {
    foreach (array_keys(config('localization.supported_locales')) as $locale) {
        $this->withSession(['locale' => $locale])
            ->get('/wordle')
            ->assertSuccessful()
            ->assertDontSee('home.word_length');
    }
});
