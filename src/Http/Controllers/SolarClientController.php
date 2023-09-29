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


    protected function get($path)
    {
        return $this->call('get', $path);
    }

    protected function post($path, $data)
    {
        return $this->call('post', $path);
    }

    protected function put($path, $data)
    {
        return $this->call('put', $path);
    }

    protected function delete($path)
    {
        return $this->call('delete', $path);
    }

    protected function call($method, $data)
    {
        if (!$this->isAccessTokenValid()) {
            $token = $this->authorize();
        } else {
            $token = $this->getAccessToken();
        }

        $call = Http::{ $this->scenario }()->withToken($token)->withHeaders($this->headers);

        if ($data) {
            $response = $call->{$method}($path, $data);
        }

        $response = $call->{$method}($path);

        return $response;
    }

    protected function authorize($path = '/auth/token', $data = ["grant_type" => "client_credentials"])
    {
        if ($this->isAccessTokenValid()) {
            return $this->getAccessToken();
        }
        return $this->reAuthorize($path, $data);
    }

    protected function reAuthorize($path = '/auth/token', $data = ["grant_type" => "client_credentials"])
    {

        //return config()->all();
        $this->clearAccessToken();

        $response = Http::{ $this->scenario }()->withHeaders(['Cache-Control' => 'no-cache'])->withBasicAuth(config('solar_client.' . $this->scenario . '.user', config('solar_client.config.user', 'no_user')), config('solar_client.' . $this->scenario . '.pass', config('solar_client.config.pass', 'no_pass')))->asForm()->post($path, $data);

        $body = $response->json();
        $status = $response->getStatusCode();
        $headers = $response->getHeaders();

        if ($response->failed()) {
            return [config('solar_client.config.user', 'no_user'),$status,$body];
        }

        return $this->setAccessToken($body);
    }

    protected function isAccessTokenValid()
    {
        $access_token = session('solar_session.access_token');

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

    protected function getAccessToken()
    {
        //hack modify expires in when access token
        $access_token = session('solar_session.access_token');

        $access_token['expires_in'] = $access_token['valid_until'] - intval(time());

        session(['solar_session.access_token' => $access_token]);

        return $access_token;
    }

    protected function setAccessToken($access_token)
    {
        $access_token['valid_until'] = strtotime('+' . (intval($access_token['expires_in']) - 360) . ' second');

        session(['solar_session.access_token' => $access_token]);

        return $access_token;
    }

    protected function clearAccessToken()
    {
        session()->forget(['solar_session.access_token']);
        return null;
    }

    public function test()
    {
        return ["Just fine"];
        //return $this->authorize();
        //return $this->authorize()->json();
    }
}
