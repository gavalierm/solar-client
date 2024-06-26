<?php

namespace Gavalierm\SolarClient\Controllers;

use Illuminate\Support\Facades\Http;
use GuzzleHttp\HandlerStack;
use Kevinrob\GuzzleCache\CacheMiddleware;

class SolarClientController
{
    protected static $instance = null;

    private $debug = false;

    protected $authorization_atempt = 0;
    protected $headers = ['Content-Type' => 'application/json'];

    protected $cache_stack;
    protected $token;

    public function __construct()
    {
        // Create default HandlerStack
        if (!$this->cache_stack) {
            $this->cache_stack = HandlerStack::create();
            // Add this middleware to the top with `push`
            $this->cache_stack->push(new CacheMiddleware(), 'solar_cache');
        }
    }

    public function debugCache($action = null)
    {
        if ($action == 'clear') {
            session()->forget('cache');
        }
        $ss = session()->all();
        unset($ss['solar_session']);
        unset($ss['_token']);
        return ['/events', '/solar/cache/clear', $ss];
    }

    public function cache($store)
    {
        if (is_array($store)) {
            session($store);
        }
        return session($store);
    }

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


    protected function call($method, $path, $data = null, $options = [])
    {
        $token = $this->authorize();

        if (!isset($token['access_token'])) {
            return $this->debug ? ["data_error" => 401, "body" => "No valid token"] : ["data_error" => 401, "body" => "No valid token"];
        }

        try {
            $config = $this->getConfig(true);
            $call = Http::baseUrl($config['credentials']['host'])->withOptions(['handler' => $this->cache_stack])->withToken($token['access_token'])->withHeaders($this->headers);
            if ($data) {
                $response = $call->{$method}($path, $data);
            } else {
                $response = $call->{$method}($path);
            }

            $status = $response->getStatusCode();
            $headers = $response->getHeaders();
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            return $this->debug ? ["data_error" => 500, "body" => $e->getMessage()] : ["data_error" => 500, "body" => "Server error"];
        }

        if ($response->failed()) {
            if ($status == 401 and $this->authorization_atempt < 3) {
                $this->reAuthorize();
                return $this->call($method, $path, $data);
            }
            return $this->debug ? ["data_error" => $status, "body" => $response->getBody()] : ["data_error" => $status, "body" => "Something went wrong"];
        }

        if (str_contains($headers['Content-Type'][0], "json")) {
            $body = $response->json();
        } else {
            $body = $response->getBody();
            foreach ($headers['Content-Type'] as $i => $v) {
                $headers['Content-Type'][$i] = str_replace(" ", "+", $v);
            }
            return response($body, $status)->withHeaders(["Content-Length" => strlen($body), "Content-type" => $headers['Content-Type'][0]]);
        }

        return $body;
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

        //echo "reAuthorize";die();
        $this->authorization_atempt++;

        $this->clearAccessToken();

        try {
            $config = $this->getConfig(true);
            $response = Http::baseUrl($config['credentials']['host'])->withHeaders(['Cache-Control' => 'no-cache'])->withBasicAuth($config['credentials']['user'], $config['credentials']['pass'])->asForm()->post($path, $data);

            $body = $response->json();
            $status = $response->getStatusCode();
            $headers = $response->getHeaders();
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            return $this->debug ? ["data_error" => 500, $e->getMessage()] : ["data_error" => 500, "Server error"];
        }

        if ($response->failed()) {
            return $this->debug ? [$status, $body] : [$status, "Something went wrong"];
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

        $access_token['revalidated'] = false;

        $access_token['expires_in'] = $access_token['valid_until'] - intval(time());
        $access_token['valid_until_date'] = date("Y-m-d H:i:s", $access_token['valid_until']);

        session(['solar_session.access_token' => $access_token]);

        return $access_token;
    }

    protected function setAccessToken($access_token)
    {
        $access_token['valid_until'] = strtotime('+' . (intval($access_token['expires_in']) - 360) . ' second');
        $access_token['valid_until_date'] = date("Y-m-d H:i:s", $access_token['valid_until']);
        $access_token['revalidated'] = true;
        session(['solar_session.access_token' => $access_token]);

        return $access_token;
    }

    protected function clearAccessToken()
    {
        session()->forget(['solar_session.access_token']);
        return null;
    }

    protected function getConfig($visible_credentials = false)
    {
        $config = config('solar_client');

        if ($visible_credentials !== true) {
            if (isset($config['credentials']['user'])) {
                $config['credentials']['user'] = "****";
            }
            if (isset($config['credentials']['pass'])) {
                $config['credentials']['pass'] = "****";
            }
        }

        return $config;
    }

    public function filterItems($data, array $filters = [])
    {
        if (empty($filters) or !is_array($data)) {
            return $data;
        }

        //return [is_array($data),$filters,$data];
        $data_ = [];
        foreach ($data as $item) {
            if (!empty($filters)) {
                foreach ($filters as $key => $value) {
                    if (is_array($value) and !in_array($item[$key], $value)) {
                        //or
                        continue 2;
                    }
                    if (!isset($item[$key]) or $filters[$key] !== $item[$key]) {
                        continue 2;
                    }
                }
            }
            $data_[] = $item;
        }
        return $data_;
    }

    public function unifySubject($subject)
    {
        if (empty($subject)) {
            return $subject;
        }
        if (is_array($subject) and isset($subject['subject']) and is_array($subject['subject']) and isset($subject['subject']['pk'])) {
            $subject['subject'] = $subject['subject'];
        } elseif (is_array($subject) and is_array($subject['person']) and isset($subject['person']['pk'])) {
            $subject['subject'] = $subject['person'];
            unset($subject['person']);
        } elseif (is_array($subject) and is_string($subject['person'])) {
            $subject['subject'] = ["pk" => $subject['person']];
            unset($subject['person']);
        } elseif (is_string($subject)) {
            $subject = ["subject" => ["pk" => $subject]];
        }

        $subject['subject']['type'] = (!empty($subject['subject']['type'])) ? $subject['subject']['type'] : "com.mediasol.solar.crm.people.model.PersonImpl";
        //$subject['resolved'] = false;
        return $subject;
    }
    public function setDebug(bool $debug)
    {
        $this->debug = $debug;
        return $this->debug;
    }

    public function getDebug()
    {
        return $this->debug;
    }

    public function getInstance()
    {
        return $this;
    }

    public function test()
    {
        $data = [];
        $data['config'] = $this->getConfig();
        //$data['clear'] = $this->clearAccessToken();
        $data['authorize'] = $this->authorize();
        $data['authorize']['access_token'] = '****';
        return $data;
    }
}
