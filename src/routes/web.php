<?php

use Illuminate\Support\Facades\Route;

Route::get('/solar', function(){
    return "Solar installed.";
});

Route::get('/solar-controller', \Gavalierm\SolarClient\SolarClient::justDoIt());