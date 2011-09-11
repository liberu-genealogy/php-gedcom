<?php
/**
 *
 */

namespace Gedcom\Record;

/**
 *
 */
class Sour extends \Gedcom\Record implements Noteable, Objectable
{
    protected $_id   = null;
    protected $_chan = null;
    
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
    protected $_note = array();
    
    /**
     *
     */
    protected $_obje = array();
    
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
    
    /**
     *
     */
    public function addObje(\Gedcom\Record\ObjeRef &$obje)
    {
        $this->_obje[] = &$obje;
    }
}
