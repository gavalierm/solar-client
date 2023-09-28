<?php

namespace Gavalierm\Controllers\SolarClientController;

use Illuminate\Support\Facades\Http;

class SolarClientController extends Controller
{
    private $test_path = '/crm/v1/people/search-all';


    public function getHostUrl()
    {
        return config('solar-api-laravel.host');
    }

    public function getCallbackUrl()
    {
        return url(config('solar-api-laravel.redirect_uri'));
    }

    public function test($path = null)
    {
        return Http::get($this->getHostUrl() . ($path ?: $this->$test_path));
    }
}
