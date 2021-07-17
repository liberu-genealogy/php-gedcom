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

class Note extends \Gedcom\Record implements Sourceable
{
    protected $_id = null;
    protected $_note = null;

    protected $_even = null;
    protected $_refn = [];
    protected $_rin = null;

    protected $_sour = [];
    protected $_chan = null;

    public function addRefn($refn = [])
    {
        $this->_refn[] = $refn;
    }

    public function addSour($sour = [])
    {
        $this->_sour[] = $sour;
    }
}
