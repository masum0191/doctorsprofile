<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Schedule::command('app:expire-subscriptions')->dailyAt('01:00');
Schedule::command('app:send-renewal-reminders')->daily();
Schedule::command('pricing:refresh-live-rates')->daily();
