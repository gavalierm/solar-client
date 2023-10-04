<?php

namespace Gavalierm\SolarClient\Controllers\Crm;

use Gavalierm\SolarClient\Controllers\SolarClientController;
use Illuminate\Support\Facades\Http;

class SolarCrmController extends SolarClientController
{
    protected $base_path = '/crm/api/v1';
    protected $people_path = '/people';
    protected $business_path = '/business-entity';
}
