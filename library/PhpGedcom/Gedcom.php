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
     * Stores the header information of the GEDCOM file.
     *
     * @var \PhpGedcom\Record\Head
     */
    protected $_head;

    /**
     * Stores the submission information for the GEDCOM file.
     *
     * @var \PhpGedcom\Record\Subn
     */
    protected $_subn;

    /**
     * Stores sources cited throughout the GEDCOM file.
     *
     * @var array
     */
    protected $_sour = array();

    /**
     * Stores all the individuals contained within the GEDCOM file.
     *
     * @var array
     */
    protected $_indi = array();

    /**
     * Stores all the families contained within the GEDCOM file.
     *
     * @var array
     */
    protected $_fam  = array();

    /**
     * Stores all the notes contained within the GEDCOM file that are not inline.
     *
     * @var array
     */
    protected $_note = array();

    /**
     * Stores all repositories that are contained within the GEDCOM file and referenced by sources.
     *
     * @var array
     */
    protected $_repo = array();

    /**
     * Stores all the media objects that are contained within the GEDCOM file.
     *
     * @var array
     */
    protected $_obje = array();

    /**
     * Stores information about all the submitters to the GEDCOM file.
     *
     * @var array
     */
    protected $_subm = array();

    /**
     * Retrieves the header record of the GEDCOM file.
     *
     * @param Record\Head $head
     */
    public function setHead(\PhpGedcom\Record\Head $head)
    {
        $this->_head = $head;
    }

    /**
     * Retrieves the submission record of the GEDCOM file.
     *
     * @param Record\Subn $subn
     */
    public function setSubn(\PhpGedcom\Record\Subn $subn)
    {
        $this->_subn = $subn;
    }

    /**
     * Adds a source to the collection of sources.
     *
     * @param Record\Sour $sour
     */
    public function addSour(\PhpGedcom\Record\Sour $sour)
    {
        $this->_sour[$sour->getId()] = $sour;
    }

    /**
     * Adds an individual to the collection of individuals.
     *
     * @param Record\Indi $indi
     */
    public function addIndi(\PhpGedcom\Record\Indi $indi)
    {
        $this->_indi[$indi->getId()] = $indi;
    }

    /**
     * Adds a family to the collection of families.
     *
     * @param Record\Fam $fam
     */
    public function addFam(\PhpGedcom\Record\Fam $fam)
    {
        $this->_fam[$fam->getId()] = $fam;
    }

    /**
     * Adds a note to the collection of notes.
     *
     * @param Record\Note $note
     */
    public function addNote(\PhpGedcom\Record\Note $note)
    {
        $this->_note[$note->getId()] = $note;
    }

    /**
     * Adds a repository to the collection of repositories.
     *
     * @param Record\Repo $repo
     */
    public function addRepo(\PhpGedcom\Record\Repo $repo)
    {
        $this->_repo[$repo->getId()] = $repo;
    }

    /**
     * Adds an object to the collection of objects.
     *
     * @param Record\Obje $obje
     */
    public function addObje(\PhpGedcom\Record\Obje $obje)
    {
        $this->_obje[$obje->getId()] = $obje;
    }

    /**
     * Adds a submitter record to the collection of submitters.
     *
     * @param Record\Subm $subm
     */
    public function addSubm(\PhpGedcom\Record\Subm $subm)
    {
        $this->_subm[$subm->getId()] = $subm;
    }

    /**
     * Gets the header information of the GEDCOM file.
     *
     * @return Record\Head
     */
    public function getHead()
    {
        return $this->_head;
    }

    /**
     * Gets the submission record of the GEDCOM file.
     *
     * @return Record\Subn
     */
    public function getSubn()
    {
        return $this->_subn;
    }

    /**
     * Gets the collection of submitters to the GEDCOM file.
     *
     * @return array
     */
    public function getSubm()
    {
        return $this->_subm;
    }

    /**
     * Gets the collection of individuals stored in the GEDCOM file.
     *
     * @return array
     */
    public function getIndi()
    {
        return $this->_indi;
    }

    /**
     * Gets the collection of families stored in the GEDCOM file.
     *
     * @return array
     */
    public function getFam()
    {
        return $this->_fam;
    }

    /**
     * Gets the collection of repositories stored in the GEDCOM file.
     *
     * @return array
     */
    public function getRepo()
    {
        return $this->_repo;
    }

    /**
     * Gets the collection of sources stored in the GEDCOM file.
     *
     * @return array
     */
    public function getSour()
    {
        return $this->_sour;
    }

    /**
     * Gets the collection of note stored in the GEDCOM file.
     *
     * @return array
     */
    public function getNote()
    {
        return $this->_note;
    }

    /**
     * Gets the collection of objects stored in the GEDCOM file.
     *
     * @return array
     */
    public function getObje()
    {
        return $this->_obje;
    }
}
