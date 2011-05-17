<?php

namespace Gedcom\Record;

require_once __DIR__ . '/../Record.php';

class Repo extends \Gedcom\Record
{
    public $addr = null;
    public $rin = null;
    public $change = null;
    public $phones = array();
    
    public $referenceNumbers = array();
    
    /**
     *
     */
    public function addPhone(\Gedcom\Record\Phone &$phone)
    {
        $this->phones[] = $phone;
    }
    
    /**
     *
     */
    public function addReferenceNumber(\Gedcom\Record\ReferenceNumber &$refn)
    {
        $this->referenceNumbers[] = $refn;
    }
}
