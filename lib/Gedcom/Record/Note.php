<?php
/**
 *
 */

namespace Gedcom\Record;

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
    public function addRefn(\Gedcom\Record\Refn &$refn)
    {
        $this->refn[] = $refn;
    }
}

