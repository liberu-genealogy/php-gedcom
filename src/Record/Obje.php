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
    protected $_id   = null;

    /**
     * @var array Array of _UID values (GEDCOM 5.5.1)
     */
    protected $_uid = [];

    /**
     * @var array Array of UID values (GEDCOM 7.0)
     */
    protected $_uid7 = [];

    protected $_form = null;
    protected $_titl = null;
    protected $_blob = null;
    protected $_rin  = null;
    protected $_chan = null;

    protected $_refn = array();

    /**
     *
     */
    protected $_note = array();

    /**
     *
     */
    public function addRefn(\Gedcom\Record\Refn $refn)
    {
        $this->_refn[] = $refn;
    }

    /**
     *
     */
    public function addNote(\Gedcom\Record\NoteRef $note)
    {
        $this->_note[] = $note;
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
