<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    /**
     * @var list<string>
     */
    protected $fillable = [
        'default_game',
        'enabled_games',
        'site_name',
        'site_tagline',
        'site_description',
        'logo_path',
        'favicon_path',
        'apple_touch_icon_path',
        'global_header_code',
        'facebook_url',
        'instagram_url',
        'youtube_url',
        'x_url',
        'pinterest_url',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'enabled_games' => 'array',
        ];
    }
}
