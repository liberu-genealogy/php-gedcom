<?php
/**
 *
 */

namespace Gedcom;

/**
 *
 *
 */
class Gedcom
{
    protected $_head = null;
    protected $_subn = null;
    
    protected $_sour = array();
    protected $_indi = array();
    protected $_fam = array();
    protected $_note = array();
    protected $_repos = array();
    protected $_obje = array();
    protected $_subm = array();
    
    /**
     *
     */
    public function addSour(\Gedcom\Record\Sour $sour)
    {
        $this->_sour[$sour->refId] = $sour;
    }
    
    /**
     *
     */
    public function addIndi(\Gedcom\Record\Indi &$indi)
    {
        $this->_indi[$indi->refId] = &$indi;
    }
    
    /**
     *
     *
     */
    public function addFam(\Gedcom\Record\Fam &$fam)
    {
        $this->_fam[$fam->refId] = &$fam;
    }
    
    /**
     *
     *
     */
    public function addNote(\Gedcom\Record\Note &$note)
    {
        $this->_note[$note->refId] = &$note;
    }
    
    /**
     *
     */
    public function addRepo(\Gedcom\Record\Repo &$repo)
    {
        $this->_repo[$repo->refId] = &$repo;
    }
    
    /**
     *
     */
    public function addObje(\Gedcom\Record\Obje &$obje)
    {
        $this->_obje[$obje->refId] = &$obje;
    }
    
    /**
     *
     */
    public function addSubm(\Gedcom\Record\Subm &$subm)
    {
        $this->_subm[$subm->refId] = &$subm;
    }
    
    /**
     *
     */
    public function &getHead()
    {
        return $this->_head;
    }
    
    /**
     *
     */
    public function &getSubn()
    {
        return $this->_subn;
    }
    
    /**
     *
     */
    public function &getSubm()
    {
        return $this->_subm;
    }
    
    /**
     *
     */
    public function &getIndi()
    {
        return $this->_indi;
    }
    
    /**
     *
     */
    public function &getFam()
    {
        return $this->_fam;
    }
    
    /**
     *
     */
    public function &getRepo()
    {
        return $this->_repo;
    }
    
    /**
     *
     */
    public function &getSour()
    {
        return $this->_sour;
    }
    
    /**
     *
     */
    public function &getNote()
    {
        return $this->_note;
    }
    
    /**
     *
     */
    public function &getObje()
    {
        return $this->_obje;
    }
}

