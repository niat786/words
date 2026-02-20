<?php

namespace App\Http\Controllers;

use App\Models\Game;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;

class HomeController extends Controller
{
    /**
     * @var array<string, array{view:string,fallback_title:string,fallback_description:string}>
     */
    protected const GAME_PAGE_MAP = [
        'wordle' => [
            'view' => 'wordle',
            'fallback_title' => 'Wordle — Play Unlimited',
            'fallback_description' => 'Play Wordle and solve the daily word challenge with multiple word lengths.',
        ],
        'spellbee' => [
            'view' => 'spell-bee',
            'fallback_title' => 'SpellBee - Spelling Bee Game',
            'fallback_description' => 'Play SpellBee: build words using 7 letters, always include the center letter, and chase the highest score.',
        ],
    ];

    public function index(): View
    {
        $defaultGame = $this->resolveDefaultGame();

        return $this->renderGamePage(
            gameKey: $defaultGame?->game_key,
            game: $defaultGame,
            canonicalUrl: route('home'),
        );
    }

    protected function resolveDefaultGame(): ?Game
    {
        $game = Game::query()
            ->where('is_default', true)
            ->first();

        if ($game !== null) {
            return $game;
        }

        return Game::query()
            ->where('game_key', 'wordle')
            ->first();
    }

    /**
     * @return array{0: array<string, mixed>|list<mixed>|null, 1: string|null}
     */
    protected function resolveSchemaMarkup(?string $adsSchemaMarkup): array
    {
        if (! is_string($adsSchemaMarkup) || trim($adsSchemaMarkup) === '') {
            return [null, null];
        }

        $trimmed = trim($adsSchemaMarkup);

        if (! Str::startsWith($trimmed, ['{', '['])) {
            return [null, $adsSchemaMarkup];
        }

        try {
            /** @var array<string, mixed>|list<mixed> $decoded */
            $decoded = json_decode($adsSchemaMarkup, true, 512, JSON_THROW_ON_ERROR);

            return [$decoded, null];
        } catch (\JsonException) {
            return [null, $adsSchemaMarkup];
        }
    }

    public function spellBee(): View
    {
        return $this->renderGamePage(
            gameKey: 'spellbee',
            game: $this->resolveGameByKey('spellbee'),
            canonicalUrl: route('spell-bee'),
        );
    }

    public function wordle(): View
    {
        return $this->renderGamePage(
            gameKey: 'wordle',
            game: $this->resolveGameByKey('wordle'),
            canonicalUrl: route('wordle'),
        );
    }

    protected function resolveGameByKey(string $gameKey): ?Game
    {
        return Game::query()
            ->where('game_key', $gameKey)
            ->first();
    }

    protected function renderGamePage(?string $gameKey, ?Game $game, string $canonicalUrl): View
    {
        $resolvedGameKey = $this->resolveSupportedGameKey($gameKey);

        if ($game === null || $game->game_key !== $resolvedGameKey) {
            $game = $this->resolveGameByKey($resolvedGameKey);
        }

        $pageConfig = self::GAME_PAGE_MAP[$resolvedGameKey];

        return view($pageConfig['view'], [
            ...$this->buildSeoViewData(
                game: $game,
                fallbackTitle: $pageConfig['fallback_title'],
                fallbackDescription: $pageConfig['fallback_description'],
                canonicalUrl: $canonicalUrl,
            ),
            'defaultGame' => $game,
        ]);
    }

    protected function resolveSupportedGameKey(?string $gameKey): string
    {
        if (is_string($gameKey) && array_key_exists($gameKey, self::GAME_PAGE_MAP)) {
            return $gameKey;
        }

        return 'wordle';
    }

    /**
     * @return array{
     *     title: string,
     *     seoTitle: string,
     *     seoDescription: string,
     *     seoKeywords: string|null,
     *     seoCanonicalUrl: string,
     *     seoRobots: string,
     *     seoOpenGraph: array<string, string>,
     *     seoTwitter: array<string, string>,
     *     seoJsonLd: array<string, mixed>|list<mixed>|null,
     *     seoRawMarkup: string|null
     * }
     */
    protected function buildSeoViewData(
        ?Game $game,
        string $fallbackTitle,
        string $fallbackDescription,
        string $canonicalUrl,
    ): array {
        $resolvedTitle = $game?->title ?: $fallbackTitle;
        $resolvedDescription = $game?->meta_description ?: $fallbackDescription;
        $resolvedCanonicalUrl = $game?->canonical_url ?: $canonicalUrl;
        $resolvedRobots = implode(', ', [
            ($game?->robots_index ?? true) ? 'index' : 'noindex',
            ($game?->robots_follow ?? true) ? 'follow' : 'nofollow',
        ]);

        [$seoJsonLd, $seoRawMarkup] = $this->resolveSchemaMarkup($game?->ads_schema_markup);

        return [
            'title' => $resolvedTitle,
            'seoTitle' => $resolvedTitle,
            'seoDescription' => $resolvedDescription,
            'seoKeywords' => $game?->focus_keyword,
            'seoCanonicalUrl' => $resolvedCanonicalUrl,
            'seoRobots' => $resolvedRobots,
            'seoOpenGraph' => [
                'title' => $game?->og_title ?: $resolvedTitle,
                'description' => $game?->og_description ?: $resolvedDescription,
                'type' => 'website',
                'url' => $resolvedCanonicalUrl,
            ],
            'seoTwitter' => [
                'card' => 'summary',
                'title' => $game?->twitter_title ?: ($game?->og_title ?: $resolvedTitle),
                'description' => $game?->twitter_description ?: ($game?->og_description ?: $resolvedDescription),
            ],
            'seoJsonLd' => $seoJsonLd,
            'seoRawMarkup' => $seoRawMarkup,
        ];
    }

}
