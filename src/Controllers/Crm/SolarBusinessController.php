<?php

namespace Gavalierm\SolarClient\Controllers\Crm;

use Illuminate\Support\Facades\Http;

class SolarBusinessController extends SolarCrmController
{

    public function search($data)
    {
        return $this->post($this->base_path . $this->business_path . '/search-all', $data);
    }

    public function getBySlug($slug)
    {
        return $this->get($this->base_path . $this->business_path . '/', $data);
    }
    public function getByEmail($email)
    {
        return $this->search(["email" => $email]);
    }
    public function getById($id)
    {
        return $this->get($this->base_path . $this->business_path . '/' . $id);
    }
    public function getAll()
    {
        return $this->search([]);
    }
}
