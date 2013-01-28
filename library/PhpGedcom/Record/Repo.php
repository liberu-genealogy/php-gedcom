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
class Repo extends \PhpGedcom\Record implements Noteable
{
    protected $_id   = null;
    
    protected $_name = null;
    protected $_addr = null;
    protected $_rin  = null;
    protected $_chan = null;
    protected $_phon = array();
    
    protected $_refn = array();
    
    /**
     *
     */
    protected $_note = array();
    
    /**
     *
     */
    public function addPhon(\PhpGedcom\Record\Phon $phon)
    {
        $this->_phon[] = $phon;
    }
    
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
}
