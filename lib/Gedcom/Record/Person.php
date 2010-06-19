<?php

namespace Gedcom\Record;

require_once __DIR__ . '/../Record.php';
require_once __DIR__ . '/Reference.php';
require_once __DIR__ . '/Event.php';
require_once __DIR__ . '/Person/Attribute.php';

use Gedcom\Record\Person\Attribute;

/**
 *
 *
 */
class Person extends \Gedcom\Record
{
    public $attributes = array();
    
    public $relationships = array();
    
    public $events = array();
    
    public $references = array();
    
    /**
     *
     *
     */
    public function addReference(Reference $reference)
    {
        $this->references[] = $reference;
    }
    
    
    /**
     *
     *
     */
    public function &addAttribute($name, $value)
    {
        $attribute = new Person\Attribute();
        $attribute->name = $name;
        $attribute->value = $value;
        
        $this->attributes[] = $attribute;
        
        return $attribute;
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
