<?php

use Gavalierm\SolarClient\Http\Controllers\SolarClientController;
use Gavalierm\SolarClient\Http\Controllers\CrmSolarClientController;
//use App\Http\Controllers\SolarClientController;
//use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return ["Solar installed."];
})->name('solar.index');

//Route::get('/solar', [Gavalierm\SolarClient\Controllers\SolarClientController\SolarClientController::class, 'index']);

Route::get('test', [SolarClientController::class,'test'])->name('solar.test');