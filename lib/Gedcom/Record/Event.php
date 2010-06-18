<?php

namespace Gedcom\Record;

require_once __DIR__ . '/../Record.php';

class Event extends \Gedcom\Record
{
    public $type = null;
    public $date = null;
    public $place = null;
    
    public $references = array();
    
    /**
     *
     *
     */
    public function addReference(Reference $reference)
    {
        $this->references[] = $reference;
    }
    
}
