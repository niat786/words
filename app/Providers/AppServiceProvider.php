<?php

namespace App\Providers;

use App\Models\Blog;
use App\Models\Setting;
use App\Observers\BlogObserver;
use App\Support\Localization\SupportedLocales;
use Carbon\CarbonImmutable;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Blog::observe(BlogObserver::class);

        $this->configureDefaults();
        $this->shareSiteSettingsWithViews();
    }

    /**
     * Configure default behaviors for production-ready applications.
     */
    protected function configureDefaults(): void
    {
        Date::use(CarbonImmutable::class);

        DB::prohibitDestructiveCommands(
            app()->isProduction(),
        );

        Password::defaults(fn (): ?Password => app()->isProduction()
            ? Password::min(12)
                ->mixedCase()
                ->letters()
                ->numbers()
                ->symbols()
                ->uncompromised()
            : null
        );
    }

    protected function shareSiteSettingsWithViews(): void
    {
        view()->composer('*', function (View $view): void {
            $view->with($this->resolveSiteSettingsSharedData());
        });
    }

    /**
     * @return array<string, mixed>
     */
    protected function resolveSiteSettingsSharedData(): array
    {
        $sharedData = [
            'globalSiteName' => config('app.name'),
            'globalSiteTagline' => null,
            'globalSiteDescription' => null,
            'globalSiteLogoUrl' => null,
            'globalSiteFaviconUrl' => null,
            'globalSiteAppleTouchIconUrl' => null,
            'globalHeaderCode' => null,
            'globalFacebookUrl' => null,
            'globalInstagramUrl' => null,
            'globalYoutubeUrl' => null,
            'globalXUrl' => null,
            'globalPinterestUrl' => null,
            'globalAvailableLocales' => SupportedLocales::all(),
            'globalCurrentLocale' => app()->getLocale(),
        ];

        try {
            $settings = Setting::query()->first();
        } catch (QueryException) {
            return $sharedData;
        }

        if ($settings === null) {
            return $sharedData;
        }

        return [
            ...$sharedData,
            'globalSiteName' => $settings->site_name ?: config('app.name'),
            'globalSiteTagline' => $settings->site_tagline,
            'globalSiteDescription' => $settings->site_description,
            'globalSiteLogoUrl' => $this->resolveMediaUrl($settings->logo_path),
            'globalSiteFaviconUrl' => $this->resolveMediaUrl($settings->favicon_path),
            'globalSiteAppleTouchIconUrl' => $this->resolveMediaUrl($settings->apple_touch_icon_path),
            'globalHeaderCode' => $settings->global_header_code,
            'globalFacebookUrl' => $settings->facebook_url,
            'globalInstagramUrl' => $settings->instagram_url,
            'globalYoutubeUrl' => $settings->youtube_url,
            'globalXUrl' => $settings->x_url,
            'globalPinterestUrl' => $settings->pinterest_url,
        ];
    }

    protected function resolveMediaUrl(?string $path): ?string
    {
        if (! is_string($path) || trim($path) === '') {
            return null;
        }

        if (Str::startsWith($path, ['http://', 'https://', '/'])) {
            return $path;
        }

        return Storage::disk('public')->url($path);
    }
}
