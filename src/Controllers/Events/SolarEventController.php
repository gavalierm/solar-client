<?php

namespace Gavalierm\SolarClient\Controllers\Events;

use Illuminate\Support\Facades\Http;

class SolarEventController extends SolarEventsController
{
    public function getBySlug($slug, array $modules = [])
    {
        $data = $this->get($this->base_path . $this->event_path . '/' . $slug);

        return $this->reachEvent($data, $modules);
    }

    public function getById($id, array $modules = [])
    {
        $data = $this->get($this->base_path . $this->event_path . '/' . $id);

        return $this->reachEvent($data, $modules);
    }
    public function getAll()
    {
        return $this->get($this->base_path . $this->event_path . '/get-events');
    }

    private function reachEvent($data, $modules = ['responsiblePerson', 'moderators', 'speakers', 'partners', 'eventRateCardItems'])
    {
        if (empty($modules)) {
            return $data;
        }

        //responsiblePerson
        if (in_array('responsiblePerson', $modules)) {
            if (!empty($data['responsiblePerson'])) {
                $data['responsiblePerson'] = $this->get($this->people_path . '/' . $data['responsiblePerson']);
            }
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
                    switch ($partner['subject']['type']) {
                        case 'com.mediasol.solar.crm.be.model.BusinessEntity':
                            $partner['subject'] = $this->get($this->business_path . '/' . $partner['subject']['pk']);
                            break;
                        case 'com.mediasol.solar.crm.people.model.PersonImpl':
                            $partner['subject'] = $this->get($this->people_path . '/' . $partner['subject']['pk']);
                            break;
                    }

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
            //$data['responsiblePerson'] = $this->crm->getPersonById($data['responsiblePerson']);
        }
        return $data;
    }
}
