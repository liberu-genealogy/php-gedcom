<?php
/**
 *
 */

namespace Gedcom\Record;

/**
 *
 *
 */
class Fam extends \Gedcom\Record implements Noteable, Sourceable, Objectable
{
    protected $_id      = null;
    protected $_chan    = null;
    
    public $husbandId = null;
    public $wifeId = null;
    
    public $nchi = null;
    
    public $children = array();
    
    protected $_even = array();
    
    protected $_slgs = array();
    
    public $submitters = array();
    
    public $refn = array();
    
    protected $_rin = null;
    
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
    protected $_obje = array();
    
    /**
     *
     *
     */
    public function &addEven($type)
    {
        $even = new Even();
        $even->type = $type;
        
        $this->_even[] = &$even;
        
        return $even;
    }
    
    /**
     *
     */
    public function addSlgs(\Gedcom\Record\Fam\Slgs &$slgs)
    {
        $this->_slgs[] = $slgs;
    }
    
    /**
     *
     *
     */
    public function addSubmitter($submitter)
    {
        $this->submitters[] = $submitter;
    }
    
    /**
     *
     *
     */
    public function addRefn(\Gedcom\Record\Refn &$refn)
    {
        $this->refn[] = $refn;
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
    public function addSour(\Gedcom\Record\SourRef &$sour)
    {
        $this->_sour[] = &$sour;
    }
    
    /**
     *
     */
    public function addObje(\Gedcom\Record\ObjeRef &$obje)
    {
        $this->_obje[] = &$obje;
    }
}
