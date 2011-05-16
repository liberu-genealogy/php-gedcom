<?php

namespace Gedcom\Record\Individual;

require_once __DIR__ . '/../../Record.php';
require_once __DIR__ . '/../Reference.php';

/**
 *
 *
 */
class Attribute extends \Gedcom\Record
{
    public $name = null;
    public $value = null;
    
    public $references = array();
    
    /**
     *
     *
     */
    public function addReference(\Gedcom\Record\Reference $reference)
    {
        $this->references[] = $reference;
    }
    
}
