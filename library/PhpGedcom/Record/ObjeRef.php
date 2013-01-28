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
class ObjeRef extends \PhpGedcom\Record implements Noteable
{
    /**
     *
     */
    protected $_isRef   = false;
    
    /**
     *
     */
    protected $_obje    = null;
    
    /**
     *
     */
    protected $_form    = null;
    
    /**
     *
     */
    protected $_titl    = null;
    
    /**
     *
     */
    protected $_file    = null;
    
    /**
     *
     */
    protected $_note = array();
    
    /**
     *
     */
    public function setIsReference($isReference = true)
    {
        $this->_isRef = $isReference;
    }
    
    /**
     *
     */
    public function getIsReference()
    {
        return $this->_isRef;
    }
    
    /**
     *
     */
    public function addNote(\PhpGedcom\Record\NoteRef $note)
    {
        $this->_note[] = $note;
    }
}

