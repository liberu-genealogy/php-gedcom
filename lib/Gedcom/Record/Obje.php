<?php
/**
 *
 */

namespace Gedcom\Record;

/**
 *
 */
class Obje extends \Gedcom\Record
{
    protected $_form = null;
    protected $_titl = null;
    protected $_blob = null;
    protected $_obje = null;
    protected $_rin = null;
    protected $_chan = null;
    
    protected $_refn = array();
    
    /**
     *
     */
    public function addRefn(\Gedcom\Record\Refn &$refn)
    {
        $this->_refn[] = $refn;
    }
}

