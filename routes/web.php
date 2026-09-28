<?php

use App\Http\Controllers\BiddingProcedureController;
use App\Http\Controllers\BiddingProcedureStepController;
use App\Http\Controllers\CityHallController;
use App\Http\Controllers\SecretarysController;
use App\Http\Controllers\ManagerController;
use App\Models\BiddingProcedureStep;
use Illuminate\Support\Facades\Route;


//City Hall
Route::get('cityhall', [CityHallController::class, 'index'])->middleware('auth');
Route::get('cityhallcreate', [CityHallController::class, 'create'])->middleware('auth');
Route::post('/cityhallstore', [CityHallController::class, 'store'])->middleware('auth');
Route::get('/cityhalledit/{id}', [CityHallController::class, 'edit'])->middleware('auth');
Route::put('/cityhall/update/{id}', [CityHallController::class, 'update'])->middleware('auth');

//bidding Secretarys
Route::get('/secretarys', [SecretarysController::class, 'index'])->middleware('auth');
Route::get('/secretaryscreate', [SecretarysController::class, 'create'])->middleware('auth');
Route::post('/secretarysstore', [SecretarysController::class, 'store'])->middleware('auth');
Route::get('/secretarysedit/{id}', [SecretarysController::class, 'edit'])->middleware('auth');
Route::put('/secretarys/update/{id}', [SecretarysController::class, 'update'])->middleware('auth');
Route::delete('/secretarys/{id}', [SecretarysController::class, 'destroy'])->middleware('auth');

//bidding procedures
Route::get('/biddingprocedures', [BiddingProcedureController::class, 'index'])->middleware('auth');
Route::get('/biddingprocedurecreate', [BiddingProcedureController::class, 'create'])->middleware('auth');
Route::post('/biddingprocedurestore', [BiddingProcedureController::class, 'store'])->middleware('auth');
Route::get('/biddingprocedureedit/{id}', [BiddingProcedureController::class, 'edit'])->middleware('auth');
Route::put('/biddingprocedures/update/{id}', [BiddingProcedureController::class, 'update'])->middleware('auth');
Route::delete('/biddingprocedures/{id}', [BiddingProcedureController::class, 'destroy'])->middleware('auth');

//biddingproceduresteps
Route::get('/biddingproceduresteps', [BiddingProcedureStepController::class, 'index'])->middleware('auth');
Route::get('/disabledbiddingproceduresteps', [BiddingProcedureStepController::class, 'disabled'])->middleware('auth');
Route::get('/biddingprocedurestepcreate', [BiddingProcedureStepController::class, 'create'])->middleware('auth');
Route::post('/biddingprocedurestepstore', [BiddingProcedureStepController::class, 'store'])->gatherMiddleware('auth');
Route::get('/biddingprocedurestepedit/{id}', [BiddingProcedureStepController::class, 'edit'])->middleware('auth');
Route::put('/biddingprocedurestep/update/{id}', [BiddingProcedureStepController::class, 'update'])->middleware('auth');

//manager
Route::get('manager', [ManagerController::class, 'index'])->middleware('auth');


//welcome
Route::get('/', function () {
    return view('welcome');
});



Route::get('invitations', function () {
    return view('mode.invitations');
})->middleware('auth');


Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});


