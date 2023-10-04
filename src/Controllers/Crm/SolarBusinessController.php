<?php

namespace Gavalierm\SolarClient\Controllers\Crm;

use Illuminate\Support\Facades\Http;

class SolarBusinessController extends SolarCrmController
{

    public function searchPerson($data)
    {
        return $this->post($this->base_path . $this->business_path . '/search-all', $data);
    }

    public function getPersonBySlug($slug)
    {
        return $this->get($this->base_path . $this->business_path . '/', $data);
    }
    public function getPersonByEmail($email)
    {
        return $this->searchPerson(["email" => $email]);
    }
    public function getPersonById($id)
    {
        return $this->get($this->base_path . $this->business_path . '/' . $id);
    }
    public function getPersonAll()
    {
        return $this->searchPerson([]);
    }
}
