<?php

namespace Gavalierm\SolarClient\Controllers\MediaLibrary;

use Gavalierm\SolarClient\Controllers\SolarClientController;

class SolarMediaLibraryController
{
    private $client = null;
    private $debug = null;

    public $base_path = '/media-library/api/v1';
    //
    public $library_objects_path = '/objects';
    public $get_library_objects_count_path = '/count';
    public $get_library_objects_path = '/all';
    public $put_library_objects_metadata_path = '/media-object-metadata';

    public $library_category_path = '/categories';
    public $get_library_category_children_path = '/children';
    public $get_library_category_children_tree_path = '/children-tree';
    public $get_library_category_pk_path = '/category';
    public $get_library_category_code_path = '/category-by-code';

    public function __construct($client = null)
    {
        if ($client === null) {
            $this->client = new SolarClientController();
        } else {
            $this->client = $client;
        }
        if ($this->client) {
            $this->debug = $this->client->getDebug();
        }
    }

    public function setDebug(bool $debug)
    {
        $this->debug = $debug;
        if ($this->client) {
            $this->debug = $this->client->setDebug($debug);
        }
        return $this->debug;
    }

    public function get($path)
    {
        if (empty($this->client)) {
            return null;
        }
        return $this->client->get($path);
    }

    public function post($path, $data)
    {
        if (empty($this->client)) {
            return null;
        }
        return $this->client->post($path);
    }

    public function put($path, $data)
    {
        if (empty($this->client)) {
            return null;
        }
        return $this->client->put($path);
    }

    public function delete($path)
    {
        if (empty($this->client)) {
            return null;
        }
        return $this->client->delete($path);
    }

    public function getMediaObject($pk, array $filters = [])
    {
        $query = http_build_query($filters);

        //pozor tu neni sub path, staci base
        $data = $this->get($this->base_path . $this->library_objects_path . '/' . $pk . '?' . $query);

        if (isset($data['data_error'])) {
            return $data;
        }

        //because query do not have implemted all filters we need to filter out unwanted items
        if (!empty($filters)) {
            $data = $this->client->fiiterItem($data, $filters);
        }

        return $data;
    }
}
