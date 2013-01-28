<?php
/**
 * php-gedcom
 *
 * php-gedcom is a library for parsing, manipulating, importing and exporting
 * GEDCOM 5.5 files in PHP 5.3+.
 *
 * @author          Kristopher Wilson <kristopherwilson@gmail.com>
 * @copyright       Copyright (c) 2010-2011, Kristopher Wilson
 * @package         php-gedcom 
 * @license         http://php-gedcom.kristopherwilson.com/license
 * @link            http://php-gedcom.kristopherwilson.com
 * @version         SVN: $Id: Place.php 76 2011-09-11 16:30:59Z kristopherwilson $
 */

namespace PhpGedcom\Record\Indi\Even;

use \PhpGedcom\Record\Noteable;
use \PhpGedcom\Record\Sourceable;

/**
 *
 */
class Plac extends \PhpGedcom\Record implements Noteable, Sourceable
{
    protected $_plac = null;
    protected $_form = null;
    
    /**
     *
     */
    protected $_note = array();
    
    /**
     *
     */
    protected $_sour = array();
    
    /**
     *
     */
    public function addNote(\PhpGedcom\Record\NoteRef $note)
    {
        $this->_note[] = $note;
    }
    
    /**
     *
     */
    public function addSour(\PhpGedcom\Record\SourRef $sour)
    {
        $this->_sour[] = $sour;
    }
}

