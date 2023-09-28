<?php

namespace Gavalierm\SolarClient\Controllers;

use Illuminate\Support\Facades\Route;

Route::get('/solar', function(){
    return "Solar installed.";
});

Route::get('/solar-controller', KdeBolo::class);