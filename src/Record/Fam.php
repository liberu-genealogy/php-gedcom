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
    protected $_id;

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
        if (isset($this->_even[strtoupper((string) $key)])) {
            return $this->_even[strtoupper((string) $key)];
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
