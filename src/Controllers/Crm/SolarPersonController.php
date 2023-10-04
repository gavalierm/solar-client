<?php

namespace Gavalierm\SolarClient\Controllers\Crm;

use Illuminate\Support\Facades\Http;

class SolarPersonController extends SolarCrmController
{

    public function searchPerson($data)
    {
        return $this->post($this->base_path . $this->people_path . '/search-all', $data);
    }

    public function getPersonBySlug($slug)
    {
        return $this->get($this->base_path . $this->people_path . '/', $data);
    }
    public function getPersonByEmail($email)
    {
        return $this->searchPerson(["email" => $email]);
    }
    public function getPersonById($id)
    {
        return $this->get($this->base_path . $this->people_path . '/' . $id);
    }
    public function getPersonAll()
    {
        return $this->searchPerson([]);
    }
}
