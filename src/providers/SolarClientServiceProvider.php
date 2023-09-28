<?php

// packages/Gavalierm/SolarClient/src/SolarClientServiceProvider.php

namespace Gavalierm\SolarClient;

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

        $this->app['router']->namespace('Gavalierm\\SolarClient\\Controllers')
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
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['solar-client'];
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

