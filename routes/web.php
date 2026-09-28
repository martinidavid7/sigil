<?php

use App\Http\Middleware\EnsureCityHallIsConfigured;
use App\Livewire\BiddingModes;
use App\Livewire\BiddingSteps;
use App\Livewire\CityHalls;
use App\Livewire\Dashboard;
use App\Livewire\Secretaries;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', Dashboard::class)->name('dashboard');
    Route::get('/prefeitura', CityHalls\Settings::class)->name('city-hall');

    Route::middleware(EnsureCityHallIsConfigured::class)->group(function () {
        Route::get('/secretarias', Secretaries\Index::class)->name('secretaries.index');
        Route::get('/modalidades', BiddingModes\Index::class)->name('bidding-modes.index');
        Route::get('/etapas', BiddingSteps\Index::class)->name('bidding-steps.index');
    });
});
