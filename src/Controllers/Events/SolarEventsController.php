<?php

namespace Gavalierm\SolarClient\Controllers\Events;

use Gavalierm\SolarClient\Controllers\SolarClientController;
use Illuminate\Support\Facades\Http;

class SolarEventsController extends SolarClientController
{
    protected $base_path = '/events/api/v1';
    protected $event_path = '/events';

    protected $people_path = '/crm/api/v1/people';
    protected $business_path = '/crm/api/v1/business-entity';
}