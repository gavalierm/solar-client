<?php

namespace Gavalierm\SolarClient\Controllers\Events;

use Gavalierm\SolarClient\Controllers\SolarClientController;

class SolarEventsController
{
    protected $client = null;
    private $debug = null;

    public $base_path = '/events/api/v1/events';
    public $get_event_by_slug_path = '/by-slug';
    public $get_events_path = '/get-events';
    public $get_events_types_path = '/event-types';
    public $post_events_create_bookings_path = '/create-bookings';
    public $get_events_issue_manual_invoice_path = '/issue-manual-invoice';

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
