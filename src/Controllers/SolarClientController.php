<?php

namespace Gavalierm\SolarClient\Controllers;

use Illuminate\Support\Facades\Http;
use GuzzleHttp\HandlerStack;
use Kevinrob\GuzzleCache\CacheMiddleware;

class SolarClientController
{
    protected $debug = false;

    protected $scenario;
    protected $authorization_atempt = 0;
    protected $headers = ['Content-Type' => 'application/json'];

    protected $cache_stack;
    protected $token;

    function __construct($scenario = 'public')
    {
        $this->scenario = $scenario;

        // Create default HandlerStack
        if (!$this->cache_stack) {
            $this->cache_stack = HandlerStack::create();
            // Add this middleware to the top with `push`
            $this->cache_stack->push(new CacheMiddleware(), 'solar_cache');
        }
    }

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

    protected function call($method, $path, $data = null, $options = [])
    {
        $token = $this->authorize();

        if (!isset($token['access_token'])) {
             return $this->debug ? [401,"No valid token"] : null;
        }

        try {
            $call = Http::{ $this->scenario }()->withOptions(['handler' => $this->cache_stack])->withToken($token['access_token'])->withHeaders($this->headers);

            if ($data) {
                $response = $call->{$method}($path, $data);
            } else {
                $response = $call->{$method}($path);
            }

            $body = $response->json();
            $status = $response->getStatusCode();
            $headers = $response->getHeaders();
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            return $this->debug ? [500,$e->getMessage()] : null;
        }

        if ($response->failed()) {
            if ($status == 401 and $this->authorization_atempt < 3) {
                $this->reAuthorize();
                return $this->call($method, $path, $data);
            }
            return $this->debug ? [$status,$body] : null;
        }
        return $body;
    }

    protected function authorize($path = '/auth/token', $data = ["grant_type" => "client_credentials"])
    {
        if ($this->isAccessTokenValid()) {
            //return $this->getAccessToken();
        }
        return $this->reAuthorize($path, $data);
    }

    protected function reAuthorize($path = '/auth/token', $data = ["grant_type" => "client_credentials"])
    {

        $this->authorization_atempt++;

        $this->clearAccessToken();

        try {
            $user = config('solar_client.' . $this->scenario . '.user') ?: (config('solar_client.default.user') ?: '');
            $pass = config('solar_client.' . $this->scenario . '.pass') ?: (config('solar_client.default.pass') ?: '');

            $response = Http::{ $this->scenario }()->withHeaders(['Cache-Control' => 'no-cache'])->withBasicAuth($user, $pass)->asForm()->post($path, $data);

            $body = $response->json();
            $status = $response->getStatusCode();
            $headers = $response->getHeaders();
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            return $this->debug ? [500,$e->getMessage()] : null;
        }

        if ($response->failed()) {
            return $this->debug ? [$status,$body] : null;
        }

        $this->authorization_atempt = 0;

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

    public function getConfig($scenario = null, $hidden = false)
    {
        $config = config('solar_client');

        if ($hidden) {
            foreach ($config as $k => $v) {
                $config[$k]['user'] = "****";
                $config[$k]['pass'] = "****";
            }
        }

        if ($scenario) {
            return $config[$scenario];
        }
        return $config;
    }

    public function setDebug(bool $debug)
    {
        $this->debug = $debug;
    }

    public function test()
    {
        return ["Just fine", $this->getConfig($this->scenario, true)];
        //return $this->authorize();
        //return $this->authorize()->json();
    }
}
