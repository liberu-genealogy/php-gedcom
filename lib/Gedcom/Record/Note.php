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
    protected $_id      = null;
    protected $_chan    = null;
    
    protected $_note    = null;
    protected $_even    = null;
    protected $_refn    = array();
    protected $_rin     = null;
    
    /**
     *
     */
    public function addRefn(\Gedcom\Record\Refn &$refn)
    {
        $this->_refn[] = $refn;
    }
}

