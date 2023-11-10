<?php

namespace Gavalierm\SolarClient\Controllers\Crm;

use Gavalierm\SolarClient\Controllers\SolarClientController;
use Illuminate\Support\Facades\Http;

class SolarCrmController extends SolarClientController
{
    public $base_path = '/crm/api/v1';
    public $person_path = '/people';
    public $business_path = '/business-entity';
}
