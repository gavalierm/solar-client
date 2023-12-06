<?php

namespace Gavalierm\SolarClient\Controllers\Eshop;

use Illuminate\Support\Facades\Http;

class SolarSitesController extends SolarEshopController
{
    public function getImage($id, $mime, $reference)
    {
        return $this->get($this->base_path . $this->images_path . '/?id=' . $id . "&contentType=" . $mime . "&entityName=" . $reference);
    }
}
