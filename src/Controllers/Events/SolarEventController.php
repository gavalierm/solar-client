<?php

namespace Gavalierm\SolarClient\Controllers\Events;

use Illuminate\Support\Facades\Http;

class SolarEventController extends SolarEventsController
{
    public function getPublicBySlug($slug, array $modules = [], array $filters = [])
    {
        $filters = array_merge(["active" => true], $filters);

        return $this->getBySlug($slug, $modules, $filters);
    }

    public function getBySlug($slug, array $modules = [], array $filters = [])
    {
        $data = $this->get($this->base_path . $this->event_path . '/' . $slug);

        foreach ($data as $key => $value) {
            if (isset($filters[$key]) and $filters[$key] !== $value) {
                return null;
            }
        }
        return $this->reachEvent($data, $modules);
    }

    public function getPublicById($id, array $modules = [], array $filters = [])
    {
        $filters = array_merge(["active" => true], $filters);

        return $this->getById($id, $modules, $filters);
    }
    public function getById($id, array $modules = [], array $filters = [])
    {
        $item = $this->get($this->base_path . $this->event_path . '/' . $id);

        if (!empty($filters)) {
            foreach ($filters as $key => $value) {
                if (!isset($item[$key]) or $filters[$key] !== $item[$key]) {
                    return null;
                }
            }
        }

        return $this->reachEvent($item, $modules);
    }

    public function getPublicAll(array $modules = [], array $filters = [])
    {
        $filters = array_merge(["active" => true], $filters);

        return $this->getAll($modules, $filters);
    }

    public function getAll(array $modules = [], array $filters = [])
    {

        $data = $this->get($this->base_path . $this->event_path . '/get-events');

        if (!empty($modules) or !empty($filters)) {
            $data_ = [];
            foreach ($data as $item) {
                if (!empty($filters)) {
                    foreach ($filters as $key => $value) {
                        if (!isset($item[$key]) or $filters[$key] !== $item[$key]) {
                            continue 2;
                        }
                    }
                }
                $data_[] = $this->reachEvent($item, $modules);
            }
            $data = $data_;
        }
        return $data;
    }

    private function reachEvent($data, $modules = ['responsiblePersons', 'moderators', 'speakers', 'partners', 'eventRateCardItems'])
    {
        if (empty($modules)) {
            return $data;
        }

        //responsiblePersons
        if (in_array('responsiblePersons', $modules)) {
            if (!empty($data['responsiblePersons'])) {
                $persons = [];
                foreach ($data['responsiblePersons'] as $person_pk) {
                    $person = $this->get($this->people_path . '/' . $person_pk);
                    //$person['type'] = 'com.mediasol.solar.crm.people.model.PersonImpl'; //hack
                    $persons[] = $person;
                }
                $data['responsiblePersons'] = $persons;
            }
        } else {
            $data['responsiblePersons'] = [];
        }

        //eventRateCardItems
        if (in_array('eventRateCardItems', $modules)) {
            if (!empty($data['eventRateCardItems'])) {
                $eventRateCardItems = [];
                foreach ($data['eventRateCardItems'] as $eventRateCardItems_id) {
                    //$eventRateCardItems[] = $this->get();
                }
                $data['eventRateCardItems'] = $eventRateCardItems;
            }
        }

        //partners
        if (in_array('partners', $modules)) {
            if (!empty($data['partners'])) {
                $partners = [];
                foreach ($data['partners'] as $partner) {
                    $type = $partner['subject']['type'];
                    switch ($type) {
                        case 'com.mediasol.solar.crm.be.model.BusinessEntity':
                            $partner['subject'] = $this->get($this->business_path . '/' . $partner['subject']['pk']);
                            break;
                        case 'com.mediasol.solar.crm.people.model.PersonImpl':
                            $partner['subject'] = $this->get($this->people_path . '/' . $partner['subject']['pk']);
                            break;
                    }
                    //resolved subject do not have type need to be refilled again
                    $partner['subject']['type'] = $type;
                    $partners[] = $partner;
                }
                $data['partners'] = $partners;
            }
        }
        //parts
        if (in_array('moderators', $modules) or in_array('speakers', $modules)) {
            if (!empty($data['parts'])) {
                foreach ($data['parts'] as $part_k => $part_v) {
                    //moderators
                    if (in_array('moderators', $modules)) {
                        $moderators = [];
                        foreach ($part_v['moderators'] as $moderator_id) {
                            $moderators[] = $this->get($this->people_path . '/' . $moderator_id);
                        }
                        $data['parts'][$part_k]['moderators'] = $moderators;
                    }
                    //scheduleItems
                    if (in_array('speakers', $modules)) {
                        $all_speakers = [];
                        foreach ($part_v['scheduleItems'] as $scheduleItems_k => $scheduleItems_v) {
                            if (in_array('speakers', $modules)) {
                                $speakers = [];
                                foreach ($scheduleItems_v['speakers'] as $speaker) {
                                    $speaker['person'] = $this->get($this->people_path . '/' . $speaker['person']);
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
            //$data['responsiblePersons'] = $this->crm->getPersonById($data['responsiblePersons']);
        }
        return $data;
    }
}
