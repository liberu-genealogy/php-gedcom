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

namespace PhpGedcom\Record;

/**
 *
 */
class Sour extends \PhpGedcom\Record implements Noteable, Objectable
{
    protected $_id   = null;
    protected $_chan = null;
    
    protected $_titl = null;
    protected $_auth = null;
    protected $_data = null;
    protected $_text = null;
    protected $_publ = null;
    protected $_repo = null;
    protected $_abbr = null;
    protected $_rin = null;
    
    protected $_refn = array();
    
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
    public function addRefn(\PhpGedcom\Record\Refn $refn)
    {
        $this->_refn[] = $refn;
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
