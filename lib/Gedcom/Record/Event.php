<?php

namespace Gedcom\Record;

require_once __DIR__ . '/../Record.php';

class Event extends \Gedcom\Record
{
    public $type = null;
    public $date = null;
    public $place = null;
    public $caus = null;
    public $age = null;
    
    public $addr = null;
    
    public $phones = array();
    
    public $agnc = null;
    
    public $references = array();
    
    /**
     *
     *
     */
    public function addReference(\Gedcom\Record\Reference &$reference)
    {
        $this->references[] = $reference;
    }
    
    /**
     *
     */
    public function addPhone(\Gedcom\Record\Phone &$phone)
    {
        $this->phones[] = $phone;
    }
    
}
