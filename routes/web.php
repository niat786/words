<?php

use App\Http\Controllers\BlogController;
use App\Http\Controllers\GameAnalyticsController;
use App\Http\Controllers\HomeController;
use App\Support\Localization\SupportedLocales;
use Illuminate\Support\Facades\Route;

Route::get('/locale/{locale}', function (string $locale) {
    abort_unless(SupportedLocales::isSupported($locale), 404);

    session()->put('locale', $locale);

    return redirect()->back();
})->name('locale.switch');

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/wordle', [HomeController::class, 'wordle'])->name('wordle');
Route::get('/spell-bee', [HomeController::class, 'spellBee'])->name('spell-bee');

Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::post('/game-analytics', [GameAnalyticsController::class, 'store'])->name('game-analytics.store');
    Route::post('/game-analytics/sync', [GameAnalyticsController::class, 'sync'])->name('game-analytics.sync');
});

require __DIR__.'/settings.php';
