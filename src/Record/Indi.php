<?php

namespace Gedcom\Record;

class Indi
{
    protected $events = [];

    public function addEven($type, $event)
    {
        if (!isset($this->events[$type])) {
            $this->events[$type] = [];
        }
        $this->events[$type][] = $event;
    }

    public function getEven($type)
    {
        return $this->events[$type] ?? [];
    }
}
