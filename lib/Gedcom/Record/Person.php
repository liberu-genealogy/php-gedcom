<?php

namespace Gedcom\Record;

require_once __DIR__ . '/../Record.php';

/**
 *
 *
 */
class Person extends \Gedcom\Record
{
    public $sources = array();
    
    /**
     *
     *
     */
    public function addSource($reference)
    {
        $this->sources[] = $reference;
    }
    
}
