<?php
/**
 *
 */

namespace Gedcom\Record\Indi;

/**
 *
 */
class Name extends \Gedcom\Record implements \Gedcom\Record\Sourceable
{
    protected $_name = null;
    protected $_npfx = null;
    protected $_givn = null;
    protected $_nick = null;
    protected $_spfx = null;
    protected $_surn = null;
    protected $_nsfx = null;
    
    /**
     *
     */
    protected $_note = array();
    
    /**
     *
     */
    protected $_sour = array();
    
    /**
     *
     */
    public function addSour(\Gedcom\Record\SourRef &$sour)
    {
        $this->_sour[] = &$sour;
    }
    
    /**
     *
     */
    public function addNote(\Gedcom\Record\NoteRef &$note)
    {
        $this->_note[] = &$note;
    }
}

