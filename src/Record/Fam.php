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

class Fam extends \Gedcom\Record implements Noteable, Sourceable, Objectable
{
    protected $_id = null;

    protected $_resn = null;

    protected $_even = [];

    protected $_husb = null;

    protected $_wife = null;

    protected $_chil = [];

    protected $_nchi = null;

    protected $_subm = [];

    protected $_slgs = [];

    protected $_refn = [];

    protected $_rin = null;

    protected $_chan = null;

    protected $_note = [];

    protected $_sour = [];

    protected $_obje = [];

    public function addEven($even)
    {
        $this->_even[$even->getType()] = $even;
    }

    /**
     * @return array
     */
    public function getAllEven()
    {
        return $this->_even;
    }

    /**
     * @return void|\Gedcom\Record\Fam\Even
     */
    public function getEven($key = '')
    {
        if (isset($this->_even[strtoupper($key)])) {
            return $this->_even[strtoupper($key)];
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
}
