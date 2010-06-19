<?php

namespace Gedcom\Record;

require_once __DIR__ . '/../Record.php';
require_once __DIR__ . '/Event.php';
require_once __DIR__ . '/Reference.php';

/**
 *
 *
 */
class Family extends \Gedcom\Record
{
    public $husbandId = null;
    public $wifeId = null;
    
    public $children = array();
    
    public $events = array();
    
    public $references = array();
    
    public $notes = array();


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
