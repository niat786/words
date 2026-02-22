<?php

namespace App\Support\Localization;

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
}
