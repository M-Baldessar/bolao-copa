<?php

namespace App\Providers;

use App\Models\Team;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        View::composer('layouts.app', function ($view) {
            if (auth()->check()) {
                $view->with('navTeams', Team::orderBy('name')->get());
            }
        });
    }
}
