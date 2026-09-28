<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\BiddingProcedure;
use App\Models\CityHall;

class AppServiceProvider extends ServiceProvider {

    /**
     * Register any application services.
     */
    public function register(): void {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void {
        
        
        View::composer('layouts.main', function ($view) {
            // Busca modalidades habilitadas
            $biddingProcedure = BiddingProcedure::where('enabled', 1)->get();           
            $view->with('biddingProcedures', $biddingProcedure);
            
            $cityHallFull = CityHall::all();            
            $view->with('cityHallFull', $cityHallFull);

            //return view('layouts.main', ['biddingProcedures' => $biddingProcedure]);
        });
    }
}
