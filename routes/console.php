<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is a great place to define all of your Closure-based
| console commands. Each Closure is bound to a command instance
| allowing a simple approach to interacting with each command.
|
*/

// Default example command
Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Example custom command for testing (optional)
Artisan::command('backup:test-schedule', function () {
    $this->info('Backup schedule test executed!');
})->describe('Test backup schedule');
