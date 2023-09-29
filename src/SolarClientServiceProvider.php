<?php

namespace Gavalierm\SolarClient;

//use Gavalierm\SolarClient;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;

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
        __DIR__ . './config/solar-client.php' => config_path('solar-client.php'),
        ]);

        //$this->app->make('Gavalierm\SolarClient\SolarClient');
        $this->app['router']->namespace('Gavalierm\\SolarClient\\Http\\Controllers')
                ->middleware(['web'])
                ->group(function () {
                    $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
                });

        if ($this->app->runningInConsole()) {
            $this->bootForConsole();
        }
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/solar-client.php', 'solar-client');

        //$this->app->singleton(SolarClient::class, function (Application $app) {
        //    return new SolarClient(config('solar-client'));
        //});
    }

    /**
     * Console-specific booting.
     *
     * @return void
     */
    protected function bootForConsole()
    {
        $this->publishes([
            __DIR__ . '/../config/solar-client.php' => config_path('solar-client.php'),
        ], 'solar-client.config');
    }
}
