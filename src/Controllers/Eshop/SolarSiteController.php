<?php

namespace Gavalierm\SolarClient\Controllers\Eshop;

use Illuminate\Support\Facades\Http;

class SolarSiteController extends SolarEshopController
{
    public function getBySlug($slug, array $modules = [])
    {
        $data = $this->get($this->base_path . $this->site_path . '/?url=' . $slug);

        return $this->reachEvent($data, $modules);
    }

    public function getAll($slug = '')
    {   
        $slug = $slug ?: env('APP_URL');
        
        return $this->get($this->base_path . $this->site_path . '/?website=' . $slug);
    }

    private function reachEvent($data, $modules = ['responsiblePerson', 'moderators', 'speakers','partners', 'eventRateCardItems'])
    {
        if (empty($modules)) {
            return $data;
        }

        return $data;
    }
}
