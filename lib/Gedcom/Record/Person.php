<?php

namespace Gedcom\Record;

require_once __DIR__ . '/../Record.php';
require_once __DIR__ . '/Event.php';

/**
 *
 *
 */
class Person extends \Gedcom\Record
{
    public $sources = array();
    
    public $events = array();
    
    /**
     *
     *
     */
    public function addSource($reference)
    {
        $this->sources[] = $reference;
    }
    
    /**
     *
     *
     */
    public function &addEvent($type)
    {
        $event = new Event();
        $event->type = $type;
        
        $this->events[] = &$event;
        
        return $event;
    }
    
}
