<?php

namespace Gavalierm\SolarClient\Controllers\Events;

use Illuminate\Support\Facades\Http;

class SolarEventTypesController extends SolarEventsController
{
    public function search($data)
    {
        return $this->post($this->base_path . $this->event_types_path . '/search-all', $data);
    }

    public function getBySlug($slug, array $rich = [], array $filters = [])
    {
        $data = $this->get($this->base_path . $this->event_types_path . '/', $slug);

        if (isset($data['data_error'])) {
            return $data;
        }

        foreach ($data as $key => $value) {
            if (isset($filters[$key]) and $filters[$key] !== $value) {
                return null;
            }
        }
        return $this->richData($data, $rich);
    }
    public function getByEmail($email)
    {
        return $this->search(["email" => $email]);
    }
    public function getById($id)
    {
        return $this->get($this->base_path . $this->event_types_path . '/' . $id);
    }
    public function getAll()
    {
        return $this->get($this->base_path . $this->event_types_path);
    }


    public function getPublicBySlug($slug, array $rich = [], array $filters = [])
    {
        $filters = array_merge(["active" => true], $filters);

        return $this->getBySlug($slug, $rich, $filters);
    }

    public function richData($data, $rich = [])
    {
        return $data;
    }
}
