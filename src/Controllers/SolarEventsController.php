<?php

namespace Gavalierm\SolarClient\Controllers;

use Illuminate\Support\Facades\Http;

    //use Illuminate\Http\Request;

    //use Acme\PageReview\Models\Page;
    //use Illuminate\Routing\Controller;
    //use Pusher\Laravel\Facades\Pusher;

class SolarEventsController extends SolarClientController
{
    protected $base_path = '/events/api/v1/events';
    protected $people_path = '/crm/api/v1/people';
    protected $business_path = '/crm/api/v1/business-entity';

    public function getBySlug($slug)
    {
        return $this->get($this->base_path . '/get-events', $data);
    }
    public function getById($id, array $modules = [])
    {
        $data = $this->get($this->base_path . '/' . $id);

        return $this->reachEvent($data, $modules);
    }
    public function getAll()
    {
        return $this->get($this->base_path . '/get-events');
    }

    private function reachEvent($data, $modules = ['responsiblePerson', 'moderators', 'speakers','partners', 'eventRateCardItems'])
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
                    $eventRateCardItems[] = $this->get();
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
                    if (in_array('speakers', $modules) or in_array('speakers', $modules)) {
                        foreach ($part_v['scheduleItems'] as $scheduleItems_k => $scheduleItems_v) {
                            if (in_array('speakers', $modules)) {
                                $speakers = [];
                                foreach ($scheduleItems_v['speakers'] as $speaker) {
                                    $speaker['person'] = $this->get($this->people_path . '/' . $speaker['person']);
                                    $speakers[] = $speaker;
                                }
                                $data['parts'][$part_k]['scheduleItems'][$scheduleItems_k]['speakers'] = $speakers;
                            }
                        }
                    }
                }
            }
            //$data['responsiblePerson'] = $this->crm->getPersonById($data['responsiblePerson']);
        }
        return $data;
    }
}
