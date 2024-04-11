<?php

use Gavalierm\SolarClient\Controllers\SolarClientController;
use Gavalierm\SolarClient\Controllers\Eshop\SolarSitesController;


Route::get('/', function () {
    return ["Solar extension installed."];
})->name('solar.index');

Route::get('get', function () {
    $solar = new SolarClientController;
    $call = explode("?call=", \Request::getRequestUri());
    return $solar->get($call[1]);
})->name('solar.path');

Route::get('cache/{action?}', [SolarClientController::class, 'debugCache'])->name('solar.debugCache');
Route::get('test', [SolarClientController::class, 'test'])->name('solar.test');
Route::get('image', [SolarSitesController::class, 'image'])->name('solar.image');


