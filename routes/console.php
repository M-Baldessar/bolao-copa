<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Sincroniza resultados a cada 5 minutos nos dias de jogos da Copa
Schedule::command('matches:sync-results')
    ->everyFiveMinutes()
    ->withoutOverlapping()
    ->runInBackground();

// Sincroniza classificação dos grupos a cada 15 minutos
Schedule::command('matches:sync-standings')
    ->everyFifteenMinutes()
    ->withoutOverlapping()
    ->runInBackground();
