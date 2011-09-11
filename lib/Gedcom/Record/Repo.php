<?php
/**
 *
 */

namespace Gedcom\Record;

/**
 *
 */
class Repo extends \Gedcom\Record implements Noteable
{
    protected $_id   = null;
    
    protected $_name = null;
    protected $_addr = null;
    protected $_rin  = null;
    protected $_chan = null;
    protected $_phon = array();
    
    protected $_refn = array();
    
    /**
     *
     */
    protected $_note = array();
    
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
    
    /**
     *
     */
    public function addNote(\Gedcom\Record\NoteRef &$note)
    {
        $this->_note[] = &$note;
    }
}
