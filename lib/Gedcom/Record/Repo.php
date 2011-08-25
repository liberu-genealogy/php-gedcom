<?php
/**
 *
 */

namespace Gedcom\Record;

/**
 *
 */
class Repo extends \Gedcom\Record
{
    protected $_name = null;
    protected $_addr = null;
    protected $_rin = null;
    protected $_chan = null;
    protected $_phon = array();
    
    protected $_refn = array();
    
    /**
     *
     */
    public function addPhon(\Gedcom\Record\Phon &$phon)
    {
        $this->_phon[] = $phon;
    }
    
    /**
     *
     */
    public function addRefn(\Gedcom\Record\Refn &$refn)
    {
        $this->_refn[] = $refn;
    }
}

