<?php

use App\Http\Controllers\ReportController;
use App\Http\Middleware\EnsureCityHallIsConfigured;
use App\Livewire\BiddingModes;
use App\Livewire\Biddings;
use App\Livewire\BiddingSteps;
use App\Livewire\CityHalls;
use App\Livewire\Dashboard;
use App\Livewire\Professionals;
use App\Livewire\Reports;
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
        Route::get('/profissionais', Professionals\Index::class)->name('professionals.index');
        Route::get('/licitacoes', Biddings\Index::class)->name('biddings.index');
        Route::get('/licitacoes/{bidding}', Biddings\Show::class)->name('biddings.show');
        Route::get('/relatorios', Reports\Index::class)->name('reports.index');

        Route::controller(ReportController::class)->prefix('relatorios')->name('reports.')->group(function () {
            Route::get('/processos', 'biddings')->name('biddings');
            Route::get('/processos/{bidding}', 'bidding')->name('bidding');
            Route::get('/etapas', 'stages')->name('stages');
        });
        Route::get('/modalidades', BiddingModes\Index::class)->name('bidding-modes.index');
        Route::get('/etapas', BiddingSteps\Index::class)->name('bidding-steps.index');
    });
});
