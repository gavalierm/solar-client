<?php

namespace Gavalierm\SolarClient\Controllers\Events;

use Gavalierm\SolarClient\Controllers\SolarClientController;
use Illuminate\Support\Facades\Http;

class SolarEventsController extends SolarClientController
{
    protected $base_path = '/events/api/v1';
    protected $event_path = '/events';
    protected $event_types_path = '/events/event-types';
}
