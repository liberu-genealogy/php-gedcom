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
 * @version         SVN: $Id$
 */

namespace PhpGedcom\Record\Indi;

use \PhpGedcom\Record\Sourceable;
use \PhpGedcom\Record\Noteable;
use \PhpGedcom\Record\Objectable;

/**
 *
 */
class Attr extends \PhpGedcom\Record\Indi\Even implements Sourceable, Noteable, Objectable
{
    protected $_type = null;
    protected $_attr = null;
    
    /**
     *
     */
    protected $_sour = array();
    
    /**
     *
     */
    protected $_note = array();
    
    /**
     *
     */
    protected $_obje = array();
    
    /**
     *
     */
    public function addSour(\PhpGedcom\Record\SourRef $sour)
    {
        $this->_sour[] = $sour;
    }
    
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
    public function addObje(\PhpGedcom\Record\ObjeRef $obje)
    {
        $this->_obje[] = $obje;
    }
}
