<?php

namespace App\Models;

use App\Support\Localization\SupportedLocales;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Blog extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'title',
        'title_translations',
        'slug',
        'content',
        'content_translations',
        'excerpt',
        'excerpt_translations',
        'featured_image_path',
        'featured_image_alt',
        'status',
        'is_featured',
        'published_at',
        'views_count',
        'reading_time_minutes',
        'seo_title',
        'meta_description',
        'focus_keyword',
        'canonical_url',
        'robots_index',
        'robots_follow',
        'og_title',
        'og_description',
        'twitter_title',
        'twitter_description',
        'schema_type',
        'schema_markup_json',
        'seo_score',
        'seo_grade',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_featured' => 'boolean',
            'published_at' => 'datetime',
            'robots_index' => 'boolean',
            'robots_follow' => 'boolean',
            'seo_score' => 'integer',
            'title_translations' => 'array',
            'content_translations' => 'array',
            'excerpt_translations' => 'array',
        ];
    }

    public function translated(string $field, ?string $locale = null): ?string
    {
        $requestedLocale = $locale ?? app()->getLocale();
        $translations = $this->getAttribute("{$field}_translations");

        if (is_array($translations)) {
            $localizedValue = $translations[$requestedLocale] ?? null;

            if (is_string($localizedValue) && $this->isMeaningfulContent($localizedValue)) {
                return $localizedValue;
            }

            $defaultValue = $translations[SupportedLocales::defaultLocale()] ?? null;

            if (is_string($defaultValue) && $this->isMeaningfulContent($defaultValue)) {
                return $defaultValue;
            }
        }

        $fallbackValue = $this->getAttribute($field);

        if (is_string($fallbackValue) && $this->isMeaningfulContent($fallbackValue)) {
            return $fallbackValue;
        }

        return null;
    }

    protected function isMeaningfulContent(string $value): bool
    {
        $decoded = html_entity_decode($value, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $textOnly = trim(preg_replace('/\x{00a0}/u', ' ', strip_tags($decoded)) ?? '');

        if ($textOnly !== '') {
            return true;
        }

        return preg_match('/<(img|svg|video|audio|iframe|object|embed|table|ul|ol|li|blockquote|pre|code)\b/i', $decoded) === 1;
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function slugRedirects(): HasMany
    {
        return $this->hasMany(BlogSlugRedirect::class);
    }

    public function scopePubliclyVisible(Builder $query): Builder
    {
        return $query->where(function (Builder $query): void {
            $query
                ->where(function (Builder $query): void {
                    $query
                        ->where('status', 'published')
                        ->where(function (Builder $query): void {
                            $query->whereNull('published_at')
                                ->orWhere('published_at', '<=', now());
                        });
                })
                ->orWhere(function (Builder $query): void {
                    $query
                        ->where('status', 'scheduled')
                        ->whereNotNull('published_at')
                        ->where('published_at', '<=', now());
                });
        });
    }
}
