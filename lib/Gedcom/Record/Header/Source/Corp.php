<?php

namespace Gedcom\Record\Header\Source;

require_once __DIR__ . '/../../../Record.php';

class Corp extends \Gedcom\Record
{
    public $corp = null;
    public $address = null;
    
    public $phones = array();
    
    /**
     *
     *
     */
    public function addPhone($phone)
    {
        $this->phones[] = $phone;
    }
}
