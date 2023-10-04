<?php

namespace Gavalierm\SolarClient\Controllers;

use Illuminate\Support\Facades\Http;

    //use Illuminate\Http\Request;

    //use Acme\PageReview\Models\Page;
    //use Illuminate\Routing\Controller;
    //use Pusher\Laravel\Facades\Pusher;

class SolarCRMController extends SolarClientController
{
    protected $people_path = '/crm/api/v1/people';
    protected $business_path = '/crm/api/v1/business-entity';

    //
    // person
    //
    public function searchPerson($data)
    {
        return $this->post($this->people_path . '/search-all', $data);
    }

    public function getPersonBySlug($slug)
    {
        return $this->get($this->people_path . '/', $data);
    }
    public function getPersonByEmail($email)
    {
        return $this->searchPerson(["email" => $email]);
    }
    public function getPersonById($id)
    {
        return $this->get($this->people_path . '/' . $id);
    }
    public function getPersonAll()
    {
        return $this->searchPerson([]);
    }

    //
    // business
    //
    public function searchBusiness($data)
    {
        return $this->post($this->business_path . '/search-all', $data);
    }

    public function getBusinessBySlug($slug)
    {
        return $this->get($this->business_path . '/', $data);
    }
    public function getBusinessByEmail($email)
    {
        return $this->searchBusiness(["email" => $email]);
    }
    public function getBusinessById($id)
    {
        return $this->get($this->business_path . '/' . $id);
    }
    public function getBusinessAll()
    {
        return $this->searchBusiness([]);
    }
}
