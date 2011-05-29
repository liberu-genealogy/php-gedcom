<?php

namespace Gedcom\Record;

require_once __DIR__ . '/../Record.php';

class Repo extends \Gedcom\Record
{
    public $addr = null;
    public $rin = null;
    public $chan = null;
    public $phon = array();
    
    public $refn = array();
    
    /**
     *
     */
    public function addPhon(\Gedcom\Record\Phone &$phon)
    {
        $this->phon[] = $phon;
    }
    
    /**
     *
     */
    public function addRefn(\Gedcom\Record\ReferenceNumber &$refn)
    {
        $this->refn[] = $refn;
    }
}
