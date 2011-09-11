<?php
/**
 *
 */

namespace Gedcom\Record\Indi;

use \Gedcom\Record\Sourceable;
use \Gedcom\Record\Noteable;
use \Gedcom\Record\Objectable;

/**
 *
 */
class Attr extends \Gedcom\Record\Even implements Sourceable, Noteable, Objectable
{
    protected $_type = null;
    protected $_attr = null;
    
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
    protected $_obje = array();
    
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
    
    /**
     *
     */
    public function addObje(\Gedcom\Record\ObjeRef &$obje)
    {
        $this->_obje[] = &$obje;
    }
}
