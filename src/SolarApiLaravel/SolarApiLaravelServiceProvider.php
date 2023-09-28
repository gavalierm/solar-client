<?php

namespace Gavalierm\SolarApiLaravel;

use Illuminate\Support\ServiceProvider;

class SolarApiLaravelServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        if (! $this->app->runningInConsole()) {
            return;
        }

        if ($this->isLumen()) {
            return;
        }

        $this->publishes([
            __DIR__ . '/../config/solar-api-laravel.php' => \config_path('solar-api-laravel.php'),
        ], 'config');
    }

    /**
     * Register the service providers.
     *
     * @return void
     */
    public function register()
    {
        if ($this->isLumen()) {
            $this->app->configure('solar-api-laravel');
        }
        $this->mergeConfigFrom(__DIR__ . '/../config/solar-api-laravel.php', 'solar-api-laravel');

        // Main Service
        $this->app->bind('Gavalierm\SolarApiLaravel\SolarApiLaravel', function ($app) {
            $config = $app['config']->get('solar-api-laravel.solar_config');

            if (! isset($config['persistent_data_handler']) && isset($app['session.store'])) {
                $config['persistent_data_handler'] = new LaravelPersistentDataHandler($app['session.store']);
            }

            if (! isset($config['url_detection_handler'])) {
                if ($this->isLumen()) {
                    $config['url_detection_handler'] = new LumenUrlDetectionHandler($app['url']);
                } else {
                    $config['url_detection_handler'] = new LaravelUrlDetectionHandler($app['url']);
                }
            }

            return new SolarApiLaravel($app['config'], $app['url'], $config);
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            'Gavalierm\SolarApiLaravel\SolarApiLaravel',
        ];
    }

    private function isLumen()
    {
        return is_a(\app(), 'Laravel\Lumen\Application');
    }
}
