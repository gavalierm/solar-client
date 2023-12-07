<?php

namespace Gavalierm\SolarClient\Controllers\Crm;

use Gavalierm\SolarClient\Controllers\SolarClientController;

class SolarCrmController
{
    private $client = null;
    private $debug = null;

    public $base_path = '/crm/api/v1';
    public $person_path = '/people';
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

    public function getPerson($pk, array $filters = [])
    {
        $query = http_build_query($filters);

        //pozor tu neni sub path, staci base
        $data = $this->get($this->base_path . $this->person_path . '/' . $pk . '?' . $query);

        if (isset($data['data_error'])) {
            return $data;
        }

        //because query do not have implemted all filters we need to filter out unwanted items
        if (!empty($filters)) {
            $data = $this->client->fiiterItem($data, $filters);
        }

        return $data;
    }
    public function getBusinessEntity($pk, array $filters = [])
    {
        $query = http_build_query($filters);

        //pozor tu neni sub path, staci base
        $data = $this->get($this->base_path . $this->business_path . '/' . $pk . '?' . $query);

        if (isset($data['data_error'])) {
            return $data;
        }

        //because query do not have implemted all filters we need to filter out unwanted items
        if (!empty($filters)) {
            $data = $this->client->fiiterItem($data, $filters);
        }

        return $data;
    }
}
