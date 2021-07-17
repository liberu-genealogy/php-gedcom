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

class Obje extends \Gedcom\Record implements Noteable
{
    protected $_id = null;

    protected $_file = [];
    protected $_rin = null;
    protected $_chan = null;

    protected $_refn = [];

    protected $_note = [];

    protected $_sour = [];

    public function addRefn($refn = [])
    {
        $this->_refn[] = $refn;
    }

    public function addNote($note = [])
    {
        $this->_note[] = $note;
    }

    public function addFile($file)
    {
        $this->_file[] = $file;
    }

    public function addSour($sour)
    {
        $this->_sour[] = $sour;
    }
}
