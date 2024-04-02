<?php

namespace Gavalierm\SolarClient\Controllers\Events;

class SolarEventController extends SolarEventsController
{



    //Create event bookings




    // Issue manual invoice for booking
    public function issueManualInvoice($pk, array $filters = [])
    {
        ///events/api/v1/events/issue-manual-invoice?bookingPk=bookingID1
        $query = http_build_query($filters);

        $data = $this->get($this->base_path . $this->get_events_issue_manual_invoice_path . '?bookingPk=' . $pk . '&' . $query);

        if (isset($data['data_error'])) {
            return null;
        }

        //because query do not have implemted all filters we need to filter out unwanted items
        if (!empty($filters)) {
            $data = $this->client->filterItems($data, $filters);
        }

        return $data;
    }

    //Get event type
    public function getEventType($pk, array $rich = [], array $filters = [])
    {
        $data = $this->get($this->base_path . $this->event_types_path . '/', $pk);

        if (isset($data['data_error'])) {
            return null;
        }

        foreach ($data as $key => $value) {
            if (isset($filters[$key]) and $filters[$key] !== $value) {
                return null;
            }
        }
        return $this->richData($data, $rich);
    }

    //Get events types
    public function getEventTypes(array $filters = [])
    {
        // /events/api/v1/events/event-types?page=0&size=100
        $query = http_build_query($filters);

        $data = $this->get($this->base_path . $this->get_events_types_path . '?' . $query);

        if (isset($data['data_error'])) {
            return null;
        }

        //because query do not have implemted all filters we need to filter out unwanted items
        if (!empty($filters)) {
            $data = $this->client->filterItems($data, $filters);
        }

        return $data;
    }

    //Get events
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

    //Get event by id
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

    //Get event by slug
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
