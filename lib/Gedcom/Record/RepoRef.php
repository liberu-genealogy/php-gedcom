<?php
/**
 *
 */

namespace Gedcom\Record;

/**
 *
 */
class RepoRef extends \Gedcom\Record implements Noteable
{
    protected $_repo = null;
    
    protected $_caln = array();
    
    /**
     *
     */
    protected $_note = array();
    
    /**
     *
     */
    public function addNote(\Gedcom\Record\NoteRef &$note)
    {
        $this->_note[] = &$note;
    }
}

