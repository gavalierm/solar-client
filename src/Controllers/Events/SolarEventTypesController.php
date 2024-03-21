<?php

namespace Gavalierm\SolarClient\Controllers\Events;

use Illuminate\Support\Facades\Http;

class SolarEventTypesController extends SolarEventsController
{
    public function search($data)
    {
        return $this->post($this->base_path . $this->event_types_path . '/search-all', $data);
    }

    public function getBySlug($slug, array $rich = [], array $filters = [])
    {
        $data = $this->get($this->base_path . $this->event_types_path . '/', $slug);

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
    public function getByEmail($email)
    {
        return $this->search(["email" => $email]);
    }
    public function getById($id)
    {
        return $this->get($this->base_path . $this->event_types_path . '/' . $id);
    }
    public function getAll()
    {
        return $this->get($this->base_path . $this->event_types_path);
    }


    public function getPublicBySlug($slug, array $rich = [], array $filters = [])
    {
        $filters = array_merge(["active" => true], $filters);

        return $this->getBySlug($slug, $rich, $filters);
    }

    public function richData($data, $rich = [])
    {
        return $data;
    }

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

    public function getEventType($pk, array $filters = [])
    {
        // /events/api/v1/events/event-types?page=0&size=100
        $query = http_build_query($filters);

        $data = $this->get($this->base_path . $this->get_events_types_path . '/' . $pk . '?' . $query);

        if (isset($data['data_error'])) {
            return null;
        }

        //because query do not have implemted all filters we need to filter out unwanted items
        if (!empty($filters)) {
            $data = $this->client->filterItems($data, $filters);
        }

        return $data;
    }

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
}
