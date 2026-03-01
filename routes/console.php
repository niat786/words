<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('blogs:clean-assets --days=2')
    ->dailyAt('03:30');

Schedule::command('games:clean-icons --days=30')
    ->monthlyOn(1, '03:45');
