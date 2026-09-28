<?php

namespace App\Providers;

use App\Models\CityHall;
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
        // O menu só exibe os demais cadastros depois que a prefeitura existe.
        View::composer('layouts.admin', function ($view) {
            $view->with('cityHallConfigured', CityHall::isConfigured());
        });
    }
}
