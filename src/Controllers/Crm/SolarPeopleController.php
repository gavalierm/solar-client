<?php

namespace Gavalierm\SolarClient\Controllers\Crm;

use Illuminate\Support\Facades\Http;

class SolarPeopleController extends SolarCrmController
{
    public function search($data)
    {
        return $this->post($this->base_path . $this->people_path . '/search-all', $data);
    }

    public function getByEmail($email)
    {
        return $this->search(["email" => $email]);
    }

    public function getBussinessEntitiesForPerson($pk, array $filters = [])
    {
        $query = http_build_query($filters);
        
        $data = $this->get($this->base_path . $this->people_path . '/business-entities/' . $pk . '?' . $query);
        
        if (isset($data['data_error'])) {
            return null;
        }

        //because query do not have implemted all filters we need to filter out unwanted items
        if (!empty($filters)) {
            $data = $this->client->filterItems($data, $filters);
        }

        return $data;
    }

    public function getPersonById($pk, array $filters = [])
    {
        $query = http_build_query($filters);

        //pozor tu neni sub path, staci base
        $data = $this->get($this->base_path . $this->people_path . '/' . $pk . '?' . $query);

        if (isset($data['data_error'])) {
            return null;
        }

        //because query do not have implemted all filters we need to filter out unwanted items
        if (!empty($filters)) {
            $data = $this->client->filterItems($data, $filters);
        }

        return $data;
    }
}
