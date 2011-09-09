<?php
/**
 *
 */

namespace Gedcom\Record;

/**
 *
 *
 */
class Indi extends \Gedcom\Record
{
    protected $_id      = null;
    protected $_chan    = null;
    
    protected $_attr = array();
    protected $_even = array();
    
    protected $_name = array();
    public $aliases = array();
    
    protected $_sex = null;
    protected $_rin = null;
    protected $_resn = null;
    protected $_rfn = null;
    protected $_afn = null;
    
    protected $_fams = array();
    protected $_famc = array();
    public $associations = array();
    
    public $submitters = array();
    protected $_anci = array();
    protected $_desi = array();
    
    public $objects = array();
    
    public $ldsIndividualOrdinances = array();

    public $refn = array();

    /**
     *
     */
    public function addName(\Gedcom\Record\Indi\Name &$name)
    {
        $this->_name[] = $name;
    }
    
    /**
     *
     */
    public function addAlias($alias)
    {
        $this->aliases[] = $alias;
    }
    
    /**
     *
     *
     */
    public function addAttr(&$attr)
    {
        $this->_attr[] = $attr;
    }
    
    /**
     *
     *
     */
    public function addEven(&$even)
    {
        $this->_even[] = &$even;
    }
    
    /**
     *
     */
    public function addAssociation(\Gedcom\Record\Indi\Association &$association)
    {
        $this->associations[] = $association;
    }
    
    /**
     *
     */
    public function addAnci($interest)
    {
        $this->_anci[] = $interest;
    }
    
    /**
     *
     *
     */
    public function addDesi($interest)
    {
        $this->_desi[] = $interest;
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
     */
    public function addRefn(\Gedcom\Record\Refn &$ref)
    {
        $this->refn[] = $ref;
    }
    
    /**
     *
     */
    public function addLdsIndividualOrdinance(\Gedcom\Record\Indi\LdsIndividualOrdinance &$ordinance)
    {
        $this->ldsIndividualOrdinances[] = $ordinance;
    }
    
    /**
     *
     *
     */
    public function getFirstAttr($type)
    {
        return current($this->getAttr($type));
    }
    
    /**
     *
     *
     */
    public function getAttr($type)
    {
        $attrs = array();
        
        foreach($this->_attr as $attr)
        {
            if($attr->name == $type)
                $attrs[] = $attr;
        }
        
        return $attrs;
    }
}
