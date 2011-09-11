<?php
/**
 *
 */

namespace Gedcom\Record\Indi;

use \Gedcom\Record\Sourceable;
use \Gedcom\Record\Noteable;

/**
 *
 */
class Asso extends \Gedcom\Record implements Sourceable, Noteable
{
    protected $_indi = null;
    protected $_rela = null;
    
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
    public function addNote(\Gedcom\Record\NoteRef &$note)
    {
        $this->_note[] = &$note;
    }
    
    /**
     *
     */
    public function addSour(\Gedcom\Record\SourRef &$sour)
    {
        $this->_sour[] = &$sour;
    }
}
