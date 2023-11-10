<?php

namespace Gavalierm\SolarClient\Controllers\Events;

use Gavalierm\SolarClient\Controllers\SolarClientController;
use Illuminate\Support\Facades\Http;

class SolarEventsController extends SolarClientController
{
    public $base_path = '/events/api/v1';
    public $event_path = '/events';
    public $event_types_path = '/events/event-types';
}
