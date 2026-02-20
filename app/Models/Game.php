<?php

namespace App\Models;

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
        'content',
        'meta_description',
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
        ];
    }

    public function analytics(): HasMany
    {
        return $this->hasMany(GameAnalytics::class);
    }
}
