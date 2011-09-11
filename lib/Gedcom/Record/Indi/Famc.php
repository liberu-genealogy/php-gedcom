<?php
/**
 *
 */

namespace Gedcom\Record\Indi;

use \Gedcom\Record\Noteable;

/**
 *
 */
class Famc extends \Gedcom\Record implements Noteable
{
    protected $_famc = null;
    protected $_pedi = null;
    
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

