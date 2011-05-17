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
    public $sources = array();
    public $referenceNumbers = array();
    
    /**
     *
     */
    public function addReferenceNumber(\Gedcom\Record\ReferenceNumber &$refn)
    {
        $this->referenceNumbers[] = $refn;
    }
}
