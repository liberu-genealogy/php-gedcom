<?php
/**
 *
 */

namespace Gedcom\Record;

/**
 *
 */
class Sour extends \Gedcom\Record
{
    protected $_titl = null;
    protected $_auth = null;
    protected $_data = null;
    protected $_text = null;
    protected $_publ = null;
    protected $_repo = null;
    protected $_abbr = null;
    protected $_rin = null;
    
    protected $_refn = array();
    
    /**
     *
     */
    public function addRefn(\Gedcom\Record\Refn &$refn)
    {
        $this->_refn[] = $refn;
    }
}

