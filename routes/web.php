<?php

use App\Http\Controllers\BlogController;
use App\Http\Controllers\GameAnalyticsController;
use App\Http\Controllers\HomeController;
use App\Support\Localization\SupportedLocales;
use Illuminate\Support\Facades\Route;

Route::get('/locale/{locale}', function (string $locale) {
    $resolvedLocale = SupportedLocales::isSupported($locale)
        ? $locale
        : SupportedLocales::fromUrlSegment($locale);
    abort_unless(is_string($resolvedLocale), 404);

    session()->put('locale', $resolvedLocale);

    $previousPath = parse_url(url()->previous(), PHP_URL_PATH) ?: '/';
    $pathWithoutLocale = SupportedLocales::stripLeadingLocaleSegment($previousPath);
    $urlPrefix = SupportedLocales::isDefault($resolvedLocale) ? '' : '/'.SupportedLocales::toUrlSegment($resolvedLocale);
    $destination = $urlPrefix.$pathWithoutLocale;

    return redirect($destination === '' ? '/' : $destination);
})->name('locale.switch');

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/wordle', [HomeController::class, 'wordle'])->name('wordle');
Route::get('/spell-bee', [HomeController::class, 'spellBee'])->name('spell-bee');
Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');

Route::prefix('/{locale}')
    ->whereIn('locale', SupportedLocales::nonDefaultUrlSegments())
    ->group(function (): void {
        Route::get('/', [HomeController::class, 'index']);
        Route::get('/wordle', [HomeController::class, 'wordle']);
        Route::get('/spell-bee', [HomeController::class, 'spellBee']);
        Route::get('/blog', [BlogController::class, 'index']);
        Route::get('/blog/{slug}', [BlogController::class, 'show']);
    });

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::post('/game-analytics', [GameAnalyticsController::class, 'store'])->name('game-analytics.store');
    Route::post('/game-analytics/sync', [GameAnalyticsController::class, 'sync'])->name('game-analytics.sync');
});

require __DIR__.'/settings.php';
