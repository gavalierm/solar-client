<?php

namespace Gavalierm\SolarClient\Controllers\Events;

use Gavalierm\SolarClient\Controllers\Crm\SolarPersonController;
use Gavalierm\SolarClient\Controllers\Crm\SolarBusinessController;
use Illuminate\Support\Facades\Http;

class SolarEventController extends SolarEventsController
{
    public function getBySlug($slug, array $filters = [])
    {

        $data = $this->get($this->base_path . $this->event_path . '/by-slug/' . $slug);

        if (isset($data['data_error'])) {
            return $data;
        }

        foreach ($data as $key => $value) {
            if (isset($filters[$key]) and $filters[$key] !== $value) {
                return null;
            }
        }
        return $data;
    }

    public function getById($id, array $filters = [])
    {

        $data = $this->get($this->base_path . $this->event_path . '/' . $id);

        if (isset($data['data_error'])) {
            return $data;
        }

        if (!empty($filters)) {
            foreach ($filters as $key => $value) {
                if (!isset($data[$key]) or $filters[$key] !== $data[$key]) {
                    return null;
                }
            }
        }

        return $data;
    }

    public function getAll(array $filters = [])
    {


        $query = http_build_query($filters);

        $path = $this->base_path . $this->event_path . '/get-events' . ((!empty($query)) ? "?" . $query : "");

        //return $path;

        $data = $this->get($path);

        //return $data;

        if (isset($data['data_error'])) {
            return $data;
        }

        /**
        if (!empty($filters)) {
            $data_ = [];
            foreach ($data as $item) {
                if (!empty($filters)) {
                    foreach ($filters as $key => $value) {
                        if (is_array($value) and !in_array($item[$key], $value)) {
                            //or
                            continue 2;
                        }
                        if (!isset($item[$key]) or $filters[$key] !== $item[$key]) {
                            continue 2;
                        }
                    }
                }
                $data_[] = $item;
            }
            $data = $data_;
        }
        **/
        return $data;
    }

    public function richDataAll($data, $rich = [])
    {

        if (empty($rich)) {
            return $data;
        }

        $data_ = [];

        foreach ($data as $item) {
            $data_[] = $this->richData($item, $rich);
        }

        return $data_;
    }

    public function richData($data, $rich = [])
    {

        if (empty($rich)) {
            return $data;
        }

        $bussines = new SolarBusinessController();
        $person = new SolarPersonController();

        //responsiblePersons
        if (in_array('responsiblePersons', $rich)) {
            if (!empty($data['responsiblePersons'])) {
                $persons = [];
                foreach ($data['responsiblePersons'] as $person_pk) {
                    $persons[] = $this->get($person->base_path . $person->person_path . "/" . $person_pk);
                }
                $data['responsiblePersons'] = $persons;
            }
        } else {
            $data['responsiblePersons'] = [];
        }

        //return $data;
        //eventRateCardItems
        if (in_array('eventRateCardItems', $rich)) {
            if (!empty($data['eventRateCardItems'])) {
                $eventRateCardItems = [];
                foreach ($data['eventRateCardItems'] as $eventRateCardItems_id) {
                    //$eventRateCardItems[] = $this->get();
                }
                $data['eventRateCardItems'] = $eventRateCardItems;
            }
        }

        //partners
        if (in_array('partners', $rich)) {
            if (!empty($data['partners'])) {
                $partners = [];
                foreach ($data['partners'] as $partner) {
                    $type = $partner['subject']['type'];

                    switch ($type) {
                        case 'com.mediasol.solar.crm.be.model.BusinessEntity':
                            $partner['subject'] = $this->get($bussines->base_path . $bussines->business_path . "/" . $partner['subject']['pk']);
                            break;
                        case 'com.mediasol.solar.crm.people.model.PersonImpl':
                            $partner['subject'] = $this->get($person->base_path . $person->person_path . "/" . $partner['subject']['pk']);
                            break;
                    }
                    //resolved subject do not have type need to be refilled again
                    $partner_['subject']['type'] = $type;
                    $partners[] = $partner;
                }
                $data['partners'] = $partners;
            }
        }

        //return $data;
        //parts
        if (in_array('moderators', $rich) or in_array('speakers', $rich)) {
            if (!empty($data['parts'])) {
                foreach ($data['parts'] as $part_k => $part_v) {
                    //moderators
                    if (in_array('moderators', $rich)) {
                        $moderators = [];
                        foreach ($part_v['moderators'] as $moderator_id) {
                            $moderators[] = $this->get($person->base_path . $person->person_path . "/" . $moderator_id);
                        }
                        $data['parts'][$part_k]['moderators'] = $moderators;
                    }
                    //scheduleItems
                    if (in_array('speakers', $rich)) {
                        $all_speakers = [];
                        foreach ($part_v['scheduleItems'] as $scheduleItems_k => $scheduleItems_v) {
                            if (in_array('speakers', $rich)) {
                                $speakers = [];
                                foreach ($scheduleItems_v['speakers'] as $speaker) {
                                    $speaker['person'] = $this->get($person->base_path . $person->person_path . "/" . $speaker['person']);
                                    $speakers[] = $speaker;
                                    $all_speakers[$speaker['person']['id']] = $speaker;
                                }
                                $data['parts'][$part_k]['scheduleItems'][$scheduleItems_k]['speakers'] = $speakers;
                            }
                        }
                        $data['speakers'] = $all_speakers;
                    }
                }
            }
        }
        return $data;
    }
}
