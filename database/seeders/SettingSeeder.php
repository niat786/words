<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Default site logo from public/images (used when no user-uploaded logo).
     */
    protected const DEFAULT_LOGO_PATH = '/images/site-logo-default.webp';

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = Setting::query()->first();

        $data = [
            'default_game' => 'wordle',
            'enabled_games' => ['wordle', 'spellbee'],
            'site_name' => config('app.name'),
            'site_tagline' => null,
            'site_description' => null,
            'logo_path' => self::DEFAULT_LOGO_PATH,
            'favicon_path' => null,
            'apple_touch_icon_path' => null,
            'global_header_code' => null,
            'facebook_url' => null,
            'instagram_url' => null,
            'youtube_url' => null,
            'x_url' => null,
            'pinterest_url' => null,
        ];

        if ($settings !== null) {
            $settings->update($data);
        } else {
            Setting::query()->create($data);
        }
    }
}
