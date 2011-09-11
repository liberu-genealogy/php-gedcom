<?php
/**
 *
 */

namespace Gedcom\Record\Fam;

use \Gedcom\Record\Objectable;
use \Gedcom\Record\Sourceable;
use \Gedcom\Record\Noteable;

/**
 *
 */
class Even extends \Gedcom\Record\Even implements Objectable, Sourceable, Noteable
{
    protected $_husb;
    protected $_wife;
    
    /**
     *
     */
    protected $_obje = array();
    
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
    public function addObje(\Gedcom\Record\ObjeRef &$obje)
    {
        $this->_obje[] = &$obje;
    }
    
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

