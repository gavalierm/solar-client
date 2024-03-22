<?php

namespace Gavalierm\SolarClient\Controllers\Crm;

use Gavalierm\SolarClient\Controllers\SolarClientController;

class SolarCrmController
{
    protected $client = null;
    private $debug = null;

    public $base_path = '/crm/api/v1';
    public $people_path = '/people';
    public $business_path = '/business-entity';

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
        if ($this->client) {
            $this->debug = $this->client->setDebug($debug);
        } else {
            $this->debug = $debug;
        }
        return $this->debug;
    }

    public function getDebug()
    {
        if ($this->client) {
            $this->debug = $this->client->getDebug();
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
