<?php

namespace Gavalierm\SolarClient\Controllers\Events;

use Gavalierm\SolarClient\Controllers\Crm\SolarPersonController;
use Gavalierm\SolarClient\Controllers\Crm\SolarBusinessController;
use Gavalierm\SolarClient\Controllers\MediaLibrary\SolarMediaLibraryController;
use Gavalierm\SolarClient\Controllers\Eshop\SolarEshopController;

class SolarEventController extends SolarEventsController
{
    public function getBySlug($slug, array $filters = [])
    {
        return $this->getEventBySlug($slug, $filters);
    }

    public function getById($pk, array $filters = [])
    {
        return $this->getEvent($pk, $filters);
    }

    public function prepareRefs($data, array $to_resolve = ['type'])
    {
        if (empty($to_resolve)) {
            return $data;
        }
        return $this->prepareRefsAll([$data], $to_resolve)[0];
    }

    public function prepareRefsAll($data, array $to_resolve = ['type'])
    {
        if (empty($to_resolve)) {
            return $data;
        }

        $data_ = [];
        foreach ($data as $item) {
            if (!empty($item['type'])) {
                if (in_array('type', $to_resolve)) {
                    $item['type'] = $this->getEventsType($item['type']);
                    $item['type']['resolved'] = true;
                } else {
                    $item['type'] = (!empty($item['type'])) ? ["id" => $item['type'],"resolved" => false] : null;
                }
            }
            //
            if (!empty($item['responsiblePersons'])) {
                $helper = [];
                foreach ($item['responsiblePersons'] as $person) {
                    $person = $this->determineSubject($person);

                    if (in_array('responsiblePersons', $to_resolve) and $person["resolved"] !== true) {
                        $person["resolved"] = true;
                    }
                    $helper[] = $person;
                }
                $item['responsiblePersons'] = $helper;
            }

            //partners
            if (!empty($item['partners'])) {
                $helper = [];
                foreach ($item['partners'] as $partner) {
                    $partner = $this->determineSubject($partner);

                    if (in_array('partners', $to_resolve) and $partner["resolved"] !== true) {
                        $partner["resolved"] = true;
                    }
                    $helper[] = $partner;
                }
                $item['partners'] = $helper;
            }

            if (!empty($item['parts'])) {
                $helper = [];
                foreach ($item['parts'] as $key => $part) {
                    //moderators
                    $moderators = [];
                    foreach ($part['moderators'] as $moderator) {
                        $moderator = $this->determineSubject($moderator);

                        if (in_array('moderators', $to_resolve) and $moderator["resolved"] !== true) {
                            $moderator["resolved"] = true;
                        }
                        $moderators[] = $moderator;
                    }
                    $part['moderators'] = $moderators;
                    //speakers
                    $scheduleItems = [];
                    foreach ($part['scheduleItems'] as $scheduleItem) {
                        $speakers = [];
                        foreach ($scheduleItem['speakers'] as $speaker) {
                            $speaker = $this->determineSubject($speaker);

                            if (in_array('moderators', $to_resolve) and $speaker["resolved"] !== true) {
                                $speaker["resolved"] = true;
                            }
                            $speakers[] = $speaker;
                        }
                        $scheduleItem['speakers'] = $speakers;
                        $scheduleItems[] = $scheduleItem;
                    }
                    $part['scheduleItems'] = $scheduleItems;


                    //
                    $helper[] = $part;
                }
                $item['parts'] = $helper;
            }
            //
            $data_[] = $item;
        }
        return $data_;
    }

    private function determineSubject($subject)
    {
        if (empty($subject)) {
            return $subject;
        }
        if (is_array($subject) and isset($subject['subject']) and is_array($subject['subject']) and isset($subject['subject']['pk'])) {
            $subject['subject'] = $subject['subject'];
        } elseif (is_array($subject) and is_array($subject['person']) and isset($subject['person']['pk'])) {
            $subject['subject'] = $subject['person'];
            unset($subject['person']);
        } elseif (is_array($subject) and is_string($subject['person'])) {
            $subject['subject'] = ["pk" => $subject['person']];
            unset($subject['person']);
        } elseif (is_string($subject)) {
            $subject = ["subject" => ["pk" => $subject]];
        }

        $subject['subject']['type'] = (!empty($subject['subject']['type'])) ? $subject['subject']['type'] : "com.mediasol.solar.crm.people.model.PersonImpl";
        $subject['resolved'] = false;
        return $subject;
    }
}
