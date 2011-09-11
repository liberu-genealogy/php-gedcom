<?php
/**
 * php-gedcom
 *
 * php-gedcom is a library for parsing, manipulating, importing and exporting
 * GEDCOM 5.5 files in PHP 5.3+.
 *
 * @author          Kristopher Wilson <kwilson@shuttlebox.net>
 * @copyright       Copyright (c) 2010-2011, Kristopher Wilson
 * @package         php-gedcom 
 * @license         http://php-gedcom.kristopherwilson.com/license
 * @link            http://php-gedcom.kristopherwilson.com
 * @version         SVN: $Id$
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
    protected $_fam  = array();
    protected $_note = array();
    protected $_repo = array();
    protected $_obje = array();
    protected $_subm = array();
    
    /**
     *
     */
    public function setHead(\Gedcom\Record\Head &$head)
    {
        $this->_head = &$head;
    }
    
    /**
     *
     */
    public function addSour(\Gedcom\Record\Sour &$sour)
    {
        $this->_sour[$sour->id] = &$sour;
    }
    
    /**
     *
     */
    public function addIndi(\Gedcom\Record\Indi &$indi)
    {
        $this->_indi[$indi->id] = &$indi;
    }
    
    /**
     *
     *
     */
    public function addFam(\Gedcom\Record\Fam &$fam)
    {
        $this->_fam[$fam->id] = &$fam;
    }
    
    /**
     *
     *
     */
    public function addNote(\Gedcom\Record\Note &$note)
    {
        $this->_note[$note->id] = &$note;
    }
    
    /**
     *
     */
    public function addRepo(\Gedcom\Record\Repo &$repo)
    {
        $this->_repo[$repo->id] = &$repo;
    }
    
    /**
     *
     */
    public function addObje(\Gedcom\Record\Obje &$obje)
    {
        $this->_obje[$obje->id] = &$obje;
    }
    
    /**
     *
     */
    public function addSubm(\Gedcom\Record\Subm &$subm)
    {
        $this->_subm[$subm->id] = &$subm;
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
