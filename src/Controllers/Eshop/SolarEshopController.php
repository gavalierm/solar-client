<?php

namespace Gavalierm\SolarClient\Controllers\Eshop;

use Gavalierm\SolarClient\Controllers\SolarClientController;
use Illuminate\Support\Facades\Http;

    //use Illuminate\Http\Request;

    //use Acme\PageReview\Models\Page;
    //use Illuminate\Routing\Controller;
    //use Pusher\Laravel\Facades\Pusher;

class SolarEshopController
{
    private $client = null;
    private $debug = null;

    public $base_path = '/eshop/api/v1/';
    public $site_path = '/sites/site';
    public $images_path = '/sites/image'; //?id=id&entityName=entityName&contentType=image/png



    public function __construct($client = null)
    {
        if ($client === null) {
            $this->client = new SolarClientController();
        } else {
            $this->client = $client;
        }
        if ($this->client) {
            $this->debug = $this->client->getDebug();
        }
    }

    public function setDebug(bool $debug)
    {
        $this->debug = $debug;
        if ($this->client) {
            $this->debug = $this->client->setDebug($debug);
        }
        return $this->debug;
    }

    public function get($path)
    {
        if (empty($this->client)) {
            return null;
        }
        return $this->client->get($path);
    }

    public function post($path, $data)
    {
        if (empty($this->client)) {
            return null;
        }
        return $this->client->post($path);
    }

    public function put($path, $data)
    {
        if (empty($this->client)) {
            return null;
        }
        return $this->client->put($path);
    }

    public function delete($path)
    {
        if (empty($this->client)) {
            return null;
        }
        return $this->client->delete($path);
    }
}
