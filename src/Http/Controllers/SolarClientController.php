<?php

namespace Gavalierm\SolarClient\Http\Controllers;

use Illuminate\Support\Facades\Http;

    //use Illuminate\Http\Request;

    //use Acme\PageReview\Models\Page;
    //use Illuminate\Routing\Controller;
    //use Pusher\Laravel\Facades\Pusher;

class SolarClientController
{
    public $scenario = 'public';
    protected $headers = ['Content-Type' => 'application/json'];


    public function get($path)
    {
        $response = Http::withHeaders($this->headers)->{ $this->$scenario }()->get($path);
        return $response;
    }

    public function post($path, $data)
    {
        $response = Http::withHeaders($this->headers)->{ $this->$scenario}()->post($path, $data);
        return $response;
    }

    public function put($path, $data)
    {
        $response = Http::withHeaders($this->headers)->{ $this->$scenario }()->put($path, $data);
        return $response;
    }

    public function delete($path)
    {
        $response = Http::withHeaders($this->headers)->{ $this->$scenario }()->delete($path);
        return $response;
    }


    private function authorize(){

    }
    public function test()
    {
        return Http::get('https://postman-echo.com/get')->json();
    }
}
