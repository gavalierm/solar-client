<?php

namespace Gavalierm\SolarClient\Controllers\Crm;

use Illuminate\Support\Facades\Http;

class SolarPersonController extends SolarCrmController
{

    public function searchPerson($data)
    {
        return $this->post($this->base_path . $this->people_path . '/search-all', $data);
    }

    public function getBySlug($slug)
    {
        return $this->get($this->base_path . $this->people_path . '/', $data);
    }
    public function getByEmail($email)
    {
        return $this->searchPerson(["email" => $email]);
    }
    public function getById($id)
    {
        return $this->get($this->base_path . $this->people_path . '/' . $id);
    }
    public function getPersonAll()
    {
        return $this->searchPerson([]);
    }
}
