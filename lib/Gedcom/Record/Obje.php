<?php
/**
 *
 */

namespace Gedcom\Record;

/**
 *
 */
class Obje extends \Gedcom\Record implements Noteable
{
    protected $_id   = null;
    
    protected $_form = null;
    protected $_titl = null;
    protected $_blob = null;
    protected $_rin  = null;
    protected $_chan = null;
    
    protected $_refn = array();
    
    /**
     *
     */
    protected $_note = array();
    
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
    public function addNote(\Gedcom\Record\NoteRef &$note)
    {
        $this->_note[] = &$note;
    }
}
