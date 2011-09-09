<?php
/**
 *
 */

namespace Gedcom\Record;

/**
 *
 */
class Subm extends \Gedcom\Record
{
    protected $_id      = null;
    protected $_chan    = null;
    
    protected $_name    = null;
    protected $_addr    = null;
    protected $_rin     = null;
    protected $_rfn     = null;
    
    protected $_lang    = array();
    protected $_phon    = array();
    
    /**
     *
     */
    public function addLang($lang)
    {
        $this->_lang[] = $lang;
    }
    
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
    public function setAddr(\Gedcom\Record\Addr &$addr)
    {
        $this->_addr = &$addr;
    }
}

