<?php

/**
 * php-gedcom.
 *
 * php-gedcom is a library for parsing, manipulating, importing and exporting
 * GEDCOM 5.5 files in PHP 5.3+.
 *
 * @author          Kristopher Wilson <kristopherwilson@gmail.com>
 * @copyright       Copyright (c) 2010-2013, Kristopher Wilson
 * @license         MIT
 *
 * @link            http://github.com/mrkrstphr/php-gedcom
 */

namespace Gedcom\Record;

class Fam extends \Gedcom\Record implements Noteable, Sourceable, Objectable, Extendable
{
    protected $_id;

    /**
     * @var array Array of _UID values (GEDCOM 5.5.1)
     */
    protected $_uid = [];

    /**
     * @var array Array of UID values (GEDCOM 7.0)
     */
    protected $_uid7 = [];

    protected $_resn;

    protected $_even = [];

    protected $_husb;

    protected $_wife;

    protected $_chil = [];

    protected $_nchi;

    protected $_subm = [];

    protected $_slgs = [];

    protected $_refn = [];

    protected $_rin;

    protected $_chan;

    protected $_note = [];

    protected $_sour = [];

    protected $_obje = [];

    protected $_extensiontags = [];

    public function addEven($recordType, $even)
    {
        if (!array_key_exists($recordType, $this->_even)) {
            $this->_even[$recordType] = [];
        }

        $this->_even[$recordType][] = $even;

        return $this;
    }

    /**
     * @return array
     */
    public function getAllEven()
    {
        return $this->_even;
    }

    /**
     * @return void|\Gedcom\Record\Fam\Even|\Gedcom\Record\Fam\Even[]
     */
    public function getEven($key = '')
    {
        $key = strtoupper((string) $key);

        if (isset($this->_even[$key])) {
            if (count($this->_even[$key]) === 1) {
                return $this->_even[$key][0];
            }

            return $this->_even[$key];
        }
    }

    public function addSlgs($slgs = [])
    {
        $this->_slgs[] = $slgs;
    }

    public function addRefn($refn = [])
    {
        $this->_refn[] = $refn;
    }

    public function addNote($note = [])
    {
        $this->_note[] = $note;
    }

    public function addSour($sour = [])
    {
        $this->_sour[] = $sour;
    }

    public function addObje($obje = [])
    {
        $this->_obje[] = $obje;
    }

    public function addExtensionTag($tag, $value)
    {
        if (strpos($tag, '_') !== 0) {
            $tag = "_$tag";
        }

        $this->_extensiontags[$tag] = $value;
    }

    public function getExtensionTag(string $tag): string
    {
        if (!isset($this->_extensiontags["_$tag"])) {
            return '';
        }

        return $this->_extensiontags["_$tag"];
    }

    /**
     * Add a _UID value (GEDCOM 5.5.1)
     * 
     * @param string $uid
     */
    public function addUid($uid = '')
    {
        $this->_uid[] = $uid;
    }

    /**
     * Get all _UID values
     * 
     * @return array
     */
    public function getAllUid()
    {
        return $this->_uid;
    }

    /**
     * Add a UID value (GEDCOM 7.0)
     * 
     * @param string $uid7
     */
    public function addUid7($uid7 = '')
    {
        $this->_uid7[] = $uid7;
    }

    /**
     * Get all UID values (GEDCOM 7.0)
     * 
     * @return array
     */
    public function getAllUid7()
    {
        return $this->_uid7;
    }
}
