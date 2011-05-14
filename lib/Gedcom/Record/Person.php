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
    public $events = array();
    
    public $fams = array();
    public $famc = array();
    
    public $references = array();
    
    public $objects = array();


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
    
    /**
     *
     *
     */
    public function getGender()
    {
        $attribute = $this->getFirstAttribute('sex');
        
        return !empty($attribute) ? $attribute->value : null;
    }
    
    
    /**
     *
     *
     */
    public function getSurname()
    {
        $name = $this->getFirstAttribute('name');
        
        if(empty($name))
            return null;
        
        $personName = explode('/', trim($name->value, '/'));
        
        if(count($personName) < 2)
        {
            return $personName[0];
        }
        
        return $personName[1];
    }
    
    
    /**
     *
     *
     */
    public function getGivenName()
    {
        $name = $this->getFirstAttribute('name');
        
        if(empty($name))
            return null;
        
        $personName = explode('/', trim($name->value, '/'));
        
        if(count($personName) < 2)
        {
            return null;
        }
        
        return $personName[0]; 
    }
    
    
    /**
     *
     *
     */
    public function getFirstAttribute($type)
    {
        return current($this->getAttribute($type));
    }
    
    
    /**
     *
     *
     */
    public function getAttribute($type)
    {
        $attributes = array();
        
        foreach($this->attributes as $attribute)
        {
            if($attribute->name == $type)
                $attributes[] = $attribute;
        }
        
        return $attributes;
    }
    
}
