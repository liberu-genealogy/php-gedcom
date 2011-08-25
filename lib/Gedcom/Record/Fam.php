<?php
/**
 *
 */

namespace Gedcom\Record;

/**
 *
 *
 */
class Fam extends \Gedcom\Record
{
    public $husbandId = null;
    public $wifeId = null;
    
    public $nchi = null;
    
    public $children = array();
    
    protected $_even = array();
    
    public $notes = array();
    
    protected $_slgs = array();
    
    public $submitters = array();
    
    public $refn = array();
    
    protected $_rin = null;


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
}
