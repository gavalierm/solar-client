<?php

namespace Gavalierm\SolarClient;

use Illuminate\Support\ServiceProvider;

class SolarClientServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([__DIR__ . '/../config/solar-client.php' => \config_path('solar-client.php'), ]);
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
    }

    /**
     * Register the service providers.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/solar-client.php', 'solar-client');
    }
}
