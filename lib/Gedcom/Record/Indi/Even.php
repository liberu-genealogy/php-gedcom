<?php
/**
 *
 */

namespace Gedcom\Record\Indi;

/**
 *
 */
class Even extends \Gedcom\Record
{
    protected $_type = null;
    protected $_date = null;
    protected $_place = null;
    protected $_caus = null;
    protected $_age = null;
    
    protected $_addr = null;
    
    protected $_phon = array();
    
    protected $_agnc = null;
    
    public $ref = array();
    
    /**
     *
     */
    public function addPhon(\Gedcom\Record\Phon &$phon)
    {
        $this->_phon[] = $phon;
    }
}

