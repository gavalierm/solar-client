<?php

use Gavalierm\SolarClient\Controllers\SolarClientController;
//use Gavalierm\SolarClient\Controllers\CrmSolarClientController;
//use App\Http\Controllers\SolarClientController;
//use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return ["Solar extension installed."];
})->name('solar.index');

Route::get('get', function () {
    $solar = new SolarClientController;
    $call = explode("?call=", \Request::getRequestUri());
    return $solar->get($call[1]);
})->name('solar.path');

Route::get('test', [SolarClientController::class, 'test'])->name('solar.test');
