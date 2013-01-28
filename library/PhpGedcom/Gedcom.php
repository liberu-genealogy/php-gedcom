<?php
/**
 * php-gedcom
 *
 * php-gedcom is a library for parsing, manipulating, importing and exporting
 * GEDCOM 5.5 files in PHP 5.3+.
 *
 * @author          Kristopher Wilson <kristopherwilson@gmail.com>
 * @copyright       Copyright (c) 2010-2011, Kristopher Wilson
 * @package         php-gedcom 
 * @license         http://php-gedcom.kristopherwilson.com/license
 * @link            http://php-gedcom.kristopherwilson.com
 * @version         SVN: $Id$
 */

namespace PhpGedcom;

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
    public function setHead(\PhpGedcom\Record\Head $head)
    {
        $this->_head = $head;
    }
    
    /**
     *
     */
    public function setSubn(\PhpGedcom\Record\Subn $subn)
    {
        $this->_subn = $subn;
    }
    
    /**
     *
     */
    public function addSour(\PhpGedcom\Record\Sour $sour)
    {
        $this->_sour[$sour->getId()] = $sour;
    }
    
    /**
     *
     */
    public function addIndi(\PhpGedcom\Record\Indi $indi)
    {
        $this->_indi[$indi->getId()] = $indi;
    }
    
    /**
     *
     */
    public function addFam(\PhpGedcom\Record\Fam $fam)
    {
        $this->_fam[$fam->getId()] = $fam;
    }
    
    /**
     *
     */
    public function addNote(\PhpGedcom\Record\Note $note)
    {
        $this->_note[$note->getId()] = $note;
    }
    
    /**
     *
     */
    public function addRepo(\PhpGedcom\Record\Repo $repo)
    {
        $this->_repo[$repo->getId()] = $repo;
    }
    
    /**
     *
     */
    public function addObje(\PhpGedcom\Record\Obje $obje)
    {
        $this->_obje[$obje->getId()] = $obje;
    }
    
    /**
     *
     */
    public function addSubm(\PhpGedcom\Record\Subm $subm)
    {
        $this->_subm[$subm->getId()] = $subm;
    }
    
    /**
     *
     */
    public function getHead()
    {
        return $this->_head;
    }
    
    /**
     *
     */
    public function getSubn()
    {
        return $this->_subn;
    }
    
    /**
     *
     */
    public function getSubm()
    {
        return $this->_subm;
    }
    
    /**
     *
     */
    public function getIndi()
    {
        return $this->_indi;
    }
    
    /**
     *
     */
    public function getFam()
    {
        return $this->_fam;
    }
    
    /**
     *
     */
    public function getRepo()
    {
        return $this->_repo;
    }
    
    /**
     *
     */
    public function getSour()
    {
        return $this->_sour;
    }
    
    /**
     *
     */
    public function getNote()
    {
        return $this->_note;
    }
    
    /**
     *
     */
    public function getObje()
    {
        return $this->_obje;
    }
    
    /**
     *
     * @throws \Exception Whenever called
     * @param string $name Ignored
     * @param string $value Ignored
     */
    public function __set($name, $value)
    {
        // prevent setting undefined attributes and not reporting the error
        throw new \Exception('Undefined property ' . $name . ' in __set');
    }
    
    /**
     * 
     * @throws \Exception Whenever called
     * @param string $name Ignored
     */
    public function __get($name)
    {
        // prevent getting undefined attributes and not reporting the error
        throw new \Exception('Undefined property ' . $name . ' in __get');
    }
}
