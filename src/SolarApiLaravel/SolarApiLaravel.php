<?php

namespace Gavalierm\SolarApiLaravel;

use Illuminate\Contracts\Config\Repository as Config;
use Illuminate\Support\Facades\Http;

class SolarApiLaravel
{

    public function __construct(array $config = [])
    {
        //$this->default_config = $config;
    }

    public function getHostUrl()
    {
        return config('solar-api-laravel.host');
    }

    private function getCallbackUrl()
    {
        return $this->url->to(config('solar-api-laravel.redirect_uri'));
    }

}
