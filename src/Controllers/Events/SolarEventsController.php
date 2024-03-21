<?php

namespace Gavalierm\SolarClient\Controllers\Events;

use Gavalierm\SolarClient\Controllers\SolarClientController;

class SolarEventsController
{
    private $client = null;
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

    public function getEvents(array $filters = [])
    {
        // at this time onlz this params are implemented in soler API
        // ?from=2023-01-01T00:10:00&to=2023-01-01T00:12:00&fulltext=Test
        $query = http_build_query($filters);

        $data = $this->get($this->base_path . $this->get_events_path . '?' . $query);

        if (isset($data['data_error'])) {
            return $data;
        }

        //because query do not have implemted all filters we need to filter out unwanted items
        if (!empty($filters)) {
            $data = $this->client->filterItems($data, $filters);
        }

        return $data;
    }

    public function getEvent($pk, array $filters = [])
    {
        $query = http_build_query($filters);

        //pozor tu neni sub path, staci base
        $data = $this->get($this->base_path . '/' . $pk . '?' . $query);

        if (isset($data['data_error'])) {
            return $data;
        }

        //because query do not have implemted all filters we need to filter out unwanted items
        if (!empty($filters)) {
            $data = $this->client->fiiterItem($data, $filters);
        }

        return $data;
    }

    public function getEventBySlug($slug, array $filters = [])
    {
        $query = http_build_query($filters);

        //pozor tu neni sub path, staci base
        $data = $this->get($this->base_path . $this->get_event_by_slug_path . '/' . $slug . '?' . $query);

        if (isset($data['data_error'])) {
            return $data;
        }

        //because query do not have implemted all filters we need to filter out unwanted items
        if (!empty($filters)) {
            $data = $this->client->filterItem($data, $filters);
        }

        return $data;
    }

}
