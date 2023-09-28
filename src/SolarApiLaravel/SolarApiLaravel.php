<?php

namespace Gavalierm\SolarApiLaravel;

use Illuminate\Contracts\Config\Repository as Config;
use Illuminate\Support\Facades\Http;

class SolarApiLaravel
{
    /**
     * @var Config
     */
    protected $config_handler;

    /**
     * @var \Illuminate\Contracts\Routing\UrlGenerator|\Laravel\Lumen\Routing\UrlGenerator
     */
    protected $url;

    /**
     * @var array
     */
    private $default_config;

    /**
     * @param Config $config_handler
     * @param \Illuminate\Contracts\Routing\UrlGenerator|\Laravel\Lumen\Routing\UrlGenerator $url
     * @param array $config
     */
    public function __construct(Config $config_handler, $url, array $config)
    {
        if (
            !is_a($url, 'Laravel\Lumen\Routing\UrlGenerator')
            && !is_a($url, 'Illuminate\Contracts\Routing\UrlGenerator')
        ) {
            throw new \InvalidArgumentException('Invalid UrlGenerator');
        }
        $this->config_handler = $config_handler;
        $this->url = $url;
        $this->default_config = $config;
    }

    public function newInstance(array $config)
    {
        $new_config = array_merge($this->default_config, $config);

        return new static($this->config_handler, $this->url, $new_config);
    }

    public function getHostUrl($host_url = null)
    {
        return $host_url ?: $this->config_handler->get('solar-api-laravel.host');
    }

    private function getCallbackUrl($callback_url = null)
    {
        $callback_url = $callback_url ?: $this->config_handler->get('solar-api-laravel.redirect_uri');

        return $this->url->to($callback_url);
    }

    public function justDoIt()
    {
        $response = Http::get('https://inspiration.goprogram.ai/');

        return $response['quote'] . ' -' . $response['author'];
    }
}
