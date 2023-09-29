<?php

//use Gavalierm\SolarClient\Controllers\SolarClientController;
//use App\Http\Controllers\SolarClientController;
//use Illuminate\Support\Facades\Route;

Route::get('/solar', function () {
    return "Solar installed.";
});

//Route::get('/solar', [Gavalierm\SolarClient\Controllers\SolarClientController\SolarClientController::class, 'index']);

//Route::get('test', [SolarClientController::class,'test']);