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

namespace PhpGedcom\Record\Indi;

use PhpGedcom\Record\Noteable;
use PhpGedcom\Record\Objectable;
use PhpGedcom\Record\Sourceable;

class Attr extends \PhpGedcom\Record\Indi\Even implements Sourceable, Noteable, Objectable
{
    protected $type = null;
    protected $_attr = null;

    protected $sour = [];

    protected $note = [];

    protected $obje = [];

    public function addSour($sour = [])
    {
        $this->sour[] = $sour;
    }

    public function addNote($note = [])
    {
        $this->note[] = $note;
    }

    public function addObje($obje = [])
    {
        $this->obje[] = $obje;
    }
}
