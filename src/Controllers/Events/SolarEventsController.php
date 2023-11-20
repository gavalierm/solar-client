<?php

namespace Gavalierm\SolarClient\Controllers\Events;

use Gavalierm\SolarClient\Controllers\SolarClientController;

class SolarEventsController extends SolarClientController
{
    public $base_path = '/events/api/v1';
    public $event_path = '/events';
    public $event_types_path = '/events/event-types';
}
