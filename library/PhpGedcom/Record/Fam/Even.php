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

namespace PhpGedcom\Record\Fam;

use PhpGedcom\Record\Noteable;
use PhpGedcom\Record\Objectable;
use PhpGedcom\Record\Sourceable;

/**
 * Event record.
 *
 * @method mixed                  getType()
 * @method \PhpGedcom\Record\Date getDate()
 * @method string                 getPlac()
 */
class Even extends \PhpGedcom\Record implements Objectable, Sourceable, Noteable
{
    protected $_type = null;
    protected $_date = null;
    protected $_plac = null;
    protected $_caus = null;
    protected $_age = null;

    protected $_addr = null;

    protected $_phon = [];

    protected $_agnc = null;

    protected $_husb = null;
    protected $_wife = null;

    protected $_obje = [];

    protected $_sour = [];

    protected $_note = [];

    public function addPhon($phon = [])
    {
        $this->_phon[] = $phon;
    }

    public function addObje($obje = [])
    {
        $this->_obje[] = $obje;
    }

    public function addSour($sour = [])
    {
        $this->_sour[] = $sour;
    }

    public function addNote($note = [])
    {
        $this->_note[] = $note;
    }
}
