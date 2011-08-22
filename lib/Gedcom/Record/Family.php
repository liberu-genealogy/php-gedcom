<?php

namespace Gedcom\Record;

require_once __DIR__ . '/../Record.php';
require_once __DIR__ . '/Event.php';

/**
 *
 *
 */
class Family extends \Gedcom\Record
{
    public $husbandId = null;
    public $wifeId = null;
    
    public $nchi = null;
    
    public $children = array();
    
    public $events = array();
    
    public $notes = array();
    
    public $sealingSpouses = array();
    
    public $submitters = array();
    
    public $refn = array();


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
     */
    public function addSealingSpouse(\Gedcom\Record\Family\SealingSpouse &$spouse)
    {
        $this->sealingSpouses[] = $spouse;
    }
    
    /**
     *
     *
     */
    public function addSubmitter($submitter)
    {
        $this->submitters[] = $submitter;
    }
    
    /**
     *
     *
     */
    public function addRefn(\Gedcom\Record\Refn &$refn)
    {
        $this->refn[] = $refn;
    }
}
