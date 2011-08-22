<?php

namespace Gedcom\Record;

require_once __DIR__ . '/../Record.php';

class Object extends \Gedcom\Record
{
    public $form = null;
    public $titl = null;
    public $blob = null;
    public $obje = null;
    public $rin = null;
    public $chan = null;
    
    public $refn = array();
    
    /**
     *
     */
    public function addRefn(\Gedcom\Record\Refn &$refn)
    {
        $this->refn[] = $refn;
    }
}
