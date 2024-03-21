<?php

namespace Gavalierm\SolarClient\Controllers\Events;

use Gavalierm\SolarClient\Controllers\Crm\SolarPersonController;
use Gavalierm\SolarClient\Controllers\Crm\SolarBusinessController;
use Gavalierm\SolarClient\Controllers\MediaLibrary\SolarMediaLibraryController;
use Gavalierm\SolarClient\Controllers\Eshop\SolarEshopController;

class SolarEventController extends SolarEventsController
{
    public function getBySlug($slug, array $filters = [])
    {
        return $this->getEventBySlug($slug, $filters);
    }

    public function getById($pk, array $filters = [])
    {
        return $this->getEvent($pk, $filters);
    }
}
