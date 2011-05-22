<?php

namespace Gedcom\Record;

require_once __DIR__ . '/../Record.php';

/**
 *
 *
 */
class Note extends \Gedcom\Record
{
    public $note = null;
    public $even = null;
    public $refn = array();
    public $rin = null;
    
    /**
     *
     */
    public function addRefn(\Gedcom\Record\ReferenceNumber &$refn)
    {
        $this->refn[] = $refn;
    }
}
