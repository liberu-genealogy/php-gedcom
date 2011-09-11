<?php
/**
 *
 */

namespace Gedcom\Record;

/**
 *
 *
 */
class Note extends \Gedcom\Record implements Sourceable
{
    protected $_id   = null;
    protected $_chan = null;
    
    protected $_note = null;
    protected $_even = null;
    protected $_refn = array();
    protected $_rin  = null;
    
    /**
     *
     */
    protected $_sour = array();
    
    /**
     *
     */
    public function addRefn(\Gedcom\Record\Refn &$refn)
    {
        $this->_refn[] = $refn;
    }
    
    /**
     *
     */
    public function addSour(\Gedcom\Record\SourRef &$sour)
    {
        $this->_sour[] = &$sour;
    }
}
