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
class Subm extends \PhpGedcom\Record implements Objectable
{
    protected $_id      = null;
    protected $_chan    = null;
    
    protected $_name    = null;
    protected $_addr    = null;
    protected $_rin     = null;
    protected $_rfn     = null;
    
    protected $_lang    = array();
    protected $_phon    = array();
    
    protected $_obje    = array();
    
    /**
     *
     */
    public function addLang($lang)
    {
        $this->_lang[] = $lang;
    }
    
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
    public function setAddr(\PhpGedcom\Record\Addr $addr)
    {
        $this->_addr = $addr;
    }
    
    /**
     *
     */
    public function addObje(\PhpGedcom\Record\ObjeRef $obje)
    {
        $this->_obje[] = $obje;
    }
}

