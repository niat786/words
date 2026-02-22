<?php

namespace App\Support\Localization;

use Illuminate\Support\Str;

class SupportedLocales
{
    /**
     * @return array<string, string>
     */
    public static function all(): array
    {
        /** @var array<string, string> $locales */
        $locales = config('localization.supported_locales', []);

        return $locales;
    }

    public static function defaultLocale(): string
    {
        $configuredDefault = (string) config('localization.default_locale', 'en_US');

        if (array_key_exists($configuredDefault, self::all())) {
            return $configuredDefault;
        }

        return 'en_US';
    }

    public static function isSupported(string $locale): bool
    {
        return array_key_exists($locale, self::all());
    }

    public static function normalize(string $locale): string
    {
        if (self::isSupported($locale)) {
            return $locale;
        }

        return self::defaultLocale();
    }

    public static function isDefault(string $locale): bool
    {
        return self::normalize($locale) === self::defaultLocale();
    }

    /**
     * @return array<string>
     */
    public static function urlSegments(): array
    {
        return array_values(array_map(
            static fn (string $locale): string => self::toUrlSegment($locale),
            array_keys(self::all()),
        ));
    }

    /**
     * @return array<string>
     */
    public static function nonDefaultUrlSegments(): array
    {
        return array_values(array_map(
            static fn (string $locale): string => self::toUrlSegment($locale),
            array_filter(
                array_keys(self::all()),
                static fn (string $locale): bool => ! self::isDefault($locale),
            ),
        ));
    }

    public static function toUrlSegment(string $locale): string
    {
        return Str::of(self::normalize($locale))
            ->replace('_', '-')
            ->lower()
            ->toString();
    }

    public static function fromUrlSegment(string $segment): ?string
    {
        $normalizedSegment = Str::lower($segment);

        foreach (array_keys(self::all()) as $locale) {
            if (self::toUrlSegment($locale) === $normalizedSegment) {
                return $locale;
            }
        }

        return null;
    }

    public static function stripLeadingLocaleSegment(string $path): string
    {
        $normalizedPath = '/'.ltrim($path, '/');
        $firstSegment = trim((string) Str::of($normalizedPath)->after('/')->before('/'), '/');

        if ($firstSegment !== '' && in_array(Str::lower($firstSegment), self::urlSegments(), true)) {
            $stripped = Str::of($normalizedPath)->after('/'.$firstSegment)->toString();

            return '/'.ltrim($stripped, '/');
        }

        return $normalizedPath;
    }
}
