<?php

namespace Gavalierm\SolarClient\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
//use Gavalierm\SolarClient\;

class SolarClientController extends Controller
{
    private $test_path = '/crm/v1/people/search-all';


    public function __invoke(Request $request)
    {
        return "Hello, World!";
    }

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
