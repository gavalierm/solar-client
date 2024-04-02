<?php

namespace Gavalierm\SolarClient\Controllers\Events;

class SolarEventController extends SolarEventsController
{



    //Create event bookings / POST
    /** POST EXAMPLE
        {
          "records" : [ {
            "event" : "e1",
            "customer" : {
              "type" : "com.mediasol.solar.crm.be.model.BusinessEntity",
              "pk" : "b1"
            },
            "rateCardItem" : "rci1",
            "contactPersons" : [ "cpId1" ],
            "paymentChannel" : "pch1",
            "invitationType" : "invId1"
          } ]
        }
     */

    public function createEventBookings($pk, array $filters = [])
    {
        ///events/api/v1/events/issue-manual-invoice?bookingPk=bookingID1
        $query = http_build_query($filters);
        $path = $this->base_path . $this->post_events_create_bookings_path . '?bookingPk=' . $pk . '&' . $query;
        //
        $result = $this->post($path, $result);

        if (isset($result['data_error'])) {
            return null;
        }

        //because query do not have implemted all filters we need to filter out unwanted items
        if (!empty($filters)) {
            $result = $this->client->filterItems($result, $filters);
        }

        return $result;
    }



    // Issue manual invoice for booking
    public function issueManualInvoice($pk, array $filters = [])
    {
        ///events/api/v1/events/issue-manual-invoice?bookingPk=bookingID1
        $query = http_build_query($filters);

        $result = $this->get($this->base_path . $this->get_events_issue_manual_invoice_path . '?bookingPk=' . $pk . '&' . $query);

        if (isset($result['data_error'])) {
            return null;
        }

        //because query do not have implemted all filters we need to filter out unwanted items
        if (!empty($filters)) {
            $result = $this->client->filterItems($result, $filters);
        }

        return $result;
    }

    //Get event type
    public function getEventType($pk, array $filters = [])
    {
        $query = http_build_query($filters);
        $path = $this->base_path . $this->get_events_types_path . '/' . $pk . '?' . $query;
        //
        $result = $this->get($path);

        if (isset($result['data_error'])) {
            $result['path'] = $path;
            return $result;
        }

        //because query do not have implemted all filters we need to filter out unwanted items
        if (!empty($filters)) {
            $result = $this->client->filterItems([$result], $filters)[0];
        }

        return $result;
    }

    //Get events types
    // pozor v API maju zly nazov eventS type
    public function getEventTypes(array $filters = [])
    {
        // /events/api/v1/events/event-types?page=0&size=100
        $query = http_build_query($filters);
        $path = $this->base_path . $this->get_events_types_path . '?' . $query;
        //
        $result = $this->get($path);

        if (isset($result['data_error'])) {
            return null;
        }

        //because query do not have implemted all filters we need to filter out unwanted items
        if (!empty($filters)) {
            $result = $this->client->filterItems($result, $filters);
        }

        return $result;
    }

    //Get events
    public function getEvents(array $filters = [])
    {
        // at this time onlz this params are implemented in soler API
        // ?from=2023-01-01T00:10:00&to=2023-01-01T00:12:00&fulltext=Test
        $query = http_build_query($filters);
        $path = $this->base_path . $this->get_all_events_path . '?' . $query;

        $result = $this->get($path);

        if (isset($result['data_error'])) {
            $result['path'] = $path;
            return $result;
        }

        //because query do not have implemted all filters we need to filter out unwanted items
        if (!empty($filters)) {
            $result = $this->client->filterItems($result, $filters);
        }

        return $result;
    }

    //Get event by id
    public function getEvent($pk, array $filters = [])
    {
        $query = http_build_query($filters);

        //pozor tu neni sub path, staci base
        $path = $this->base_path . '/' . $pk . '?' . $query;
        $result = $this->get($path);

        if (isset($result['data_error'])) {
            $result['path'] = $path;
            return $result;
        }

        //because query do not have implemted all filters we need to filter out unwanted items
        if (!empty($filters)) {
            $result = $this->client->filterItems([$result], $filters)[0];
        }

        return $result;
    }

    //Get event by slug
    public function getEventBySlug($slug, array $filters = [])
    {
        $query = http_build_query($filters);

        //pozor tu neni sub path, staci base
        $path = $this->base_path . $this->get_event_by_slug_path . '/' . $slug . '?' . $query;
        $result = $this->get($path);

        if (isset($result['data_error'])) {
            $result['path'] = $path;
            return $result;
        }

        //because query do not have implemted all filters we need to filter out unwanted items
        if (!empty($filters)) {
            $result = $this->client->filterItems([$result], $filters)[0];
        }

        return $result;
    }
}
