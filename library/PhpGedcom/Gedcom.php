<?php
/**
 * php-gedcom
 *
 * php-gedcom is a library for parsing, manipulating, importing and exporting
 * GEDCOM 5.5 files in PHP 5.3+.
 *
 * @author          Kristopher Wilson <kristopherwilson@gmail.com>
 * @copyright       Copyright (c) 2010-2013, Kristopher Wilson
 * @package         php-gedcom 
 * @license         GPL-3.0
 * @link            http://github.com/mrkrstphr/php-gedcom
 */

namespace PhpGedcom;

/**
 *
 *
 */
class Gedcom
{
    /**
     * @var \PhpGedcom\Record\Head
     */
    protected $_head;

    /**
     * @var \PhpGedcom\Record\Subn
     */
    protected $_subn;

    /**
     * @var array
     */
    protected $_sour = array();

    /**
     * @var array
     */
    protected $_indi = array();

    /**
     * @var array
     */
    protected $_fam  = array();

    /**
     * @var array
     */
    protected $_note = array();

    /**
     * @var array
     */
    protected $_repo = array();

    /**
     * @var array
     */
    protected $_obje = array();

    /**
     * @var array
     */
    protected $_subm = array();

    /**
     *
     * @param Record\Head $head
     */
    public function setHead(\PhpGedcom\Record\Head $head)
    {
        $this->_head = $head;
    }

    /**
     * @param Record\Subn $subn
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
     * @return Record\Head
     */
    public function getHead()
    {
        return $this->_head;
    }

    /**
     * @return Record\Subn
     */
    public function getSubn()
    {
        return $this->_subn;
    }

    /**
     *
     * @return array
     */
    public function getSubm()
    {
        return $this->_subm;
    }

    /**
     *
     * @return array
     */
    public function getIndi()
    {
        return $this->_indi;
    }

    /**
     *
     * @return array
     */
    public function getFam()
    {
        return $this->_fam;
    }

    /**
     *
     * @return array
     */
    public function getRepo()
    {
        return $this->_repo;
    }

    /**
     *
     * @return array
     */
    public function getSour()
    {
        return $this->_sour;
    }

    /**
     *
     * @return array
     */
    public function getNote()
    {
        return $this->_note;
    }

    /**
     *
     * @return array
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
