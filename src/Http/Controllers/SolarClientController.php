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
        return $this->call('get', $path);
    }

    public function post($path, $data)
    {
        return $this->call('post', $path);
    }

    public function put($path, $data)
    {
        return $this->call('put', $path);
    }

    public function delete($path)
    {
        return $this->call('delete', $path);
    }

    private function call($method, $data)
    {
        if (!$this->isAccessTokenValid()) {
            $token = $this->authorize();
        } else {
            $token = $this->getAccessToken();
        }

        $call = Http::withToken($token)->withHeaders($this->headers)->{ $this->scenario }();

        if ($data) {
            $response = $call->{$method}($path, $data);
        }

        $response = $call->{$method}($path);

        return $response;
    }

    private function authorize($path = '/auth/token', $data = ["grant_type" => "client_credentials"])
    {
        if ($this->isAccessTokenValid()) {
            return $this->getAccessToken();
        }
        return $this->reAuthorize($path, $data);
    }

    private function reAuthorize($path = '/auth/token', $data = ["grant_type" => "client_credentials"])
    {

        //return config()->all();
        $this->clearAccessToken();

        $response = Http::withBasicAuth(config('solar_client.config.user','no_user'), config('solar_client.config.pass','no_pass'))->withHeaders(['Content-Type' => 'application/x-www-form-urlencoded'])->{ $this->scenario }()->post($path, $data);

        $body = $response->getBody();
        $status = $response->getStatus();
        $headers = $response->getHeaders();

        $this->setAccessToken($body);

        return $this->getAccessToken();
    }

    private function isAccessTokenValid()
    {
        $access_token = session('solar.access_token');

        if (empty($access_token)) {
            return false;
        }

        if (empty($access_token['valid_until'])) {
            return false;
        }

        if (intval($access_token['valid_until']) < intval(time())) {
            return false;
        }

        return true;
    }

    public function getAccessToken()
    {
        //hack modify expires in when access token
        $access_token = session('solar.access_token');

        $access_token['expires_in'] = $access_token['valid_until'] - intval(time());

        $access_token = session(['solar.access_token' => $access_token]);

        return $access_token;
    }

    public function setAccessToken($access_token)
    {
        //_log($token);
        $access_token['valid_until'] = strtotime('+' . (intval($access_token['expires_in']) - 360) . ' second');

        $access_token = session(['solar.access_token' => $access_token]);

        return $access_token;
    }

    public function clearAccessToken()
    {
        session()->forget(['solar.access_token']);
        return null;
    }

    public function test()
    {
        return $this->authorize();
        return $this->authorize()->json();
    }
}
