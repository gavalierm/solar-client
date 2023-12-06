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

        /**
        // macros
        Http::macro('demo', function () {
            return Http::baseUrl(config('solar_client.demo.host') ?: config('solar_client.default.host'));
        });

        Http::macro('dev', function () {
            return Http::baseUrl(config('solar_client.dev.host') ?: config('solar_client.default.host'));
        });

        Http::macro('public', function () {
            return Http::baseUrl(config('solar_client.public.host') ?: config('solar_client.default.host'));
        });
        **/
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/config/solar-client.php', 'solar_client');

        //$this->app->singleton(SolarClient::class, function (Application $app) {
        //    return new SolarClient(config('solar-client'));
        //});
    }
}
