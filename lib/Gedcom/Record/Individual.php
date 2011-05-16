<?php

namespace Gedcom\Record;

require_once __DIR__ . '/../Record.php';
require_once __DIR__ . '/Reference.php';
require_once __DIR__ . '/Event.php';
require_once __DIR__ . '/Individual/Attribute.php';

use Gedcom\Record\Individual\Attribute;

/**
 *
 *
 */
class Individual extends \Gedcom\Record
{
    public $attributes = array();
    public $events = array();
    
    public $names = array();
    
    public $sex = null;
    public $rin = null;
    public $resn = null;
    public $rfn = null;
    public $afn = null;
    
    public $fams = array();
    public $famc = array();
    
    public $objects = array();

    /**
     *
     */
    public function addName(\Gedcom\Record\Individual\Name &$name)
    {
        $this->names[] = $name;
    }
    
    /**
     *
     *
     */
    public function addAttribute(&$attribute)
    {
        $this->attributes[] = $attribute;
    }
    
    /**
     *
     *
     */
    public function addEvent(&$event)
    {
        $this->events[] = &$event;
    }
    
    /**
     *
     *
     */
    public function addSpouseFamily(&$family)
    {
        $this->fams[] = $family;
    }
    
    /**
     *
     */
    public function addChildFamily(&$family)
    {
        $this->famc[] = $family;
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
