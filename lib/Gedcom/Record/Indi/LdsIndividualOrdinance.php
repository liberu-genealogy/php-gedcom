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
class LdsIndividualOrdinance extends \Gedcom\Record implements Sourceable, Noteable
{
    protected $_type;
    protected $_stat;
    protected $_date;
    protected $_plac;
    protected $_temp;
    
    /**
     *
     */
    protected $_sour = array();
    
    /**
     *
     */
    protected $_note = array();
    
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
