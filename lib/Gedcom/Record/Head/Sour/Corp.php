<?php

namespace Gedcom\Record\Head\Sour;

require_once __DIR__ . '/../../../Record.php';

class Corp extends \Gedcom\Record
{
    protected $_corp = null;
    protected $_addr = null;
    
    protected $_phon = array();
    
    /**
     *
     *
     */
    public function addPhon($phon)
    {
        $this->_phon[] = $phon;
    }
}
