<?php

namespace Gavalierm\SolarClient\Http\Controllers;

use Illuminate\Support\Facades\Http;

    //use Illuminate\Http\Request;

    //use Acme\PageReview\Models\Page;
    //use Illuminate\Routing\Controller;
    //use Pusher\Laravel\Facades\Pusher;

class EventsSolarClientController extends SolarClientController
{
    protected $base_path = '/events/api/v1/events';

    public function getEventBySlug($slug)
    {
        return $this->get($this->base_path . '/get-events', $data);
    }
    public function getEventById($id)
    {
    }
    public function getEvents()
    {
        return $this->get($this->base_path . '/get-events');
    }
}