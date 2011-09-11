<?php
/**
 *
 */

namespace Gedcom\Record\Indi;

use \Gedcom\Record\Objectable;
use \Gedcom\Record\Sourceable;
use \Gedcom\Record\Noteable;

/**
 *
 */
class Even extends \Gedcom\Record implements Objectable, Sourceable, Noteable
{
    protected $_type = null;
    protected $_date = null;
    protected $_place = null;
    protected $_caus = null;
    protected $_age = null;
    
    protected $_addr = null;
    
    protected $_phon = array();
    
    protected $_agnc = null;
    
    public $ref = array();
    
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
    public function addPhon(\Gedcom\Record\Phon &$phon)
    {
        $this->_phon[] = $phon;
    }
    
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

