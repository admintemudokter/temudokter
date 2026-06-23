<?php

use App\Console\Commands\AutoExpireConsultations;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

/*
|--------------------------------------------------------------------------
| KonsulKU Scheduled Commands
|--------------------------------------------------------------------------
|
| Run: php artisan schedule:work  (for local development)
|      Add to crontab: * * * * * cd /path/to/konsulku && php artisan schedule:run >> /dev/null 2>&1
|
*/

// Auto-expire active consultations whose timer has run out — every minute
Schedule::command('consultations:expire')->everyMinute()->withoutOverlapping();
