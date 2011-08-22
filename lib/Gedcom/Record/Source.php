<?php

namespace Gedcom\Record;

require_once __DIR__ . '/../Record.php';

class Source extends \Gedcom\Record
{
    public $titl = null;
    public $auth = null;
    public $data = null;
    public $text = null;
    public $publ = null;
    public $repo = null;
    public $abbr = null;
    public $rin = null;
    
    public $refn = array();
    
    /**
     *
     */
    public function addRefn(\Gedcom\Record\Refn &$refn)
    {
        $this->refn[] = $refn;
    }
}
