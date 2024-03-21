<?php

namespace Gavalierm\SolarClient\Controllers\Eshop;

use Illuminate\Support\Facades\Http;

class SolarSitesController extends SolarEshopController
{
    public function getImage($id, $mime, $reference)
    {
        $path = $this->base_path . $this->images_path . '/?id=' . $id . "&contentType=" . $mime . "&entityName=" . $reference;
        $data = $this->get($path);

        if($this->getDebug() and isset($data['data_error'])){
            $data['path'] = $path;
            return $data;
        }
        return $data;
    }
}
