<?php

namespace App\Models;

use App\Support\Localization\SupportedLocales;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Game extends Model
{
    use HasFactory;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'game_key',
        'title',
        'title_translations',
        'content',
        'content_translations',
        'meta_description',
        'meta_description_translations',
        'ads_schema_markup',
        'focus_keyword',
        'canonical_url',
        'robots_index',
        'robots_follow',
        'og_title',
        'og_description',
        'twitter_title',
        'twitter_description',
        'is_active',
        'is_default',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'robots_index' => 'boolean',
            'robots_follow' => 'boolean',
            'is_active' => 'boolean',
            'is_default' => 'boolean',
            'title_translations' => 'array',
            'content_translations' => 'array',
            'meta_description_translations' => 'array',
        ];
    }

    public function translated(string $field, ?string $locale = null): ?string
    {
        $requestedLocale = $locale ?? app()->getLocale();
        $translations = $this->getAttribute("{$field}_translations");

        if (is_array($translations)) {
            $localizedValue = $translations[$requestedLocale] ?? null;

            if (is_string($localizedValue) && trim($localizedValue) !== '') {
                return $localizedValue;
            }

            $defaultValue = $translations[SupportedLocales::defaultLocale()] ?? null;

            if (is_string($defaultValue) && trim($defaultValue) !== '') {
                return $defaultValue;
            }
        }

        $fallbackValue = $this->getAttribute($field);

        if (is_string($fallbackValue) && trim($fallbackValue) !== '') {
            return $fallbackValue;
        }

        return null;
    }

    public function analytics(): HasMany
    {
        return $this->hasMany(GameAnalytics::class);
    }
}
