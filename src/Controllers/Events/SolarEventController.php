<?php

namespace Gavalierm\SolarClient\Controllers\Events;

class SolarEventController extends SolarEventsController
{

    public function getEvents(array $filters = [])
    {
        // at this time onlz this params are implemented in soler API
        // ?from=2023-01-01T00:10:00&to=2023-01-01T00:12:00&fulltext=Test
        $query = http_build_query($filters);
        $path = $this->base_path . $this->get_events_path . '?' . $query;

        $data = $this->get($path);

        if (isset($data['data_error'])) {
            $data['path'] = $path;
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
        $path = $this->base_path . '/' . $pk . '?' . $query;
        $data = $this->get($path);

        if (isset($data['data_error'])) {
            $data['path'] = $path;
            return $data;
        }

        //because query do not have implemted all filters we need to filter out unwanted items
        if (!empty($filters)) {
            $data = $this->client->filterItems([$data], $filters)[0];
        }

        return $data;
    }

    public function getEventBySlug($slug, array $filters = [])
    {
        $query = http_build_query($filters);

        //pozor tu neni sub path, staci base
        $path = $this->base_path . $this->get_event_by_slug_path . '/' . $slug . '?' . $query;
        $data = $this->get($path);

        if (isset($data['data_error'])) {
            $data['path'] = $path;
            return $data;
        }

        //because query do not have implemted all filters we need to filter out unwanted items
        if (!empty($filters)) {
            $data = $this->client->filterItems([$data], $filters)[0];
        }

        return $data;
    }
}
