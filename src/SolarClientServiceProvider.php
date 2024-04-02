<?php

namespace Gavalierm\SolarClient;

//use Gavalierm\SolarClient;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Http;

class SolarClientServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {

        $this->publishes([
            __DIR__ . '/config/solar-client.php' => config_path('solar-client.php'),
        ]);

        //$this->app->make('Gavalierm\SolarClient\SolarClient');
        $this->app['router']->middleware(['web'])->prefix('solar')->group(function () {
            $this->loadRoutesFrom(__DIR__ . '/routes/web.php');
        });
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/config/solar-client.php', 'solar_client');
    }
}
