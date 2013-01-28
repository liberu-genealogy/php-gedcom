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
 * Stores the data from the HEAD section of a GEDCOM 5.5 file.
 */
class Head extends \PhpGedcom\Record
{
    /**
     * 
     */
    protected $_sour = null;
    
    /**
     *
     */
    protected $_dest = null;
    
    /**
     *
     */
    protected $_date = null;
    
    /**
     *
     */
    protected $_subm = null;
    
    /**
     *
     */
    protected $_subn = null;
    
    /**
     *
     */
    protected $_file = null;
    
    /**
     *
     */
    protected $_copr = null;
    
    /**
     *
     */
    protected $_gedc = null;
    
    /**
     *
     */
    protected $_char = null;
    
    /**
     *
     */
    protected $_lang = null;
    
    /**
     *
     */
    protected $_plac = null;
    
    /**
     *
     */
    protected $_note = null;
    
    /**
     * 
     * @param \PhpGedcom\Record\Head\Sour $sour
     */
    public function setSour(\PhpGedcom\Record\Head\Sour $sour)
    {
        $this->_sour = $sour;
    }
    
    /**
     *
     * @return \PhpGedcom\Record\Head\Sour
     */
    public function getSour()
    {
        return $this->_sour;
    }
    
    /**
     *
     * @param \PhpGedcom\Record\Head\Date $date
     */
    public function setDate(\PhpGedcom\Record\Head\Date $date)
    {
        $this->_date = $date;
    }
    
    /**
     *
     * @return \PhpGedcom\Record\Head\Date
     */
    public function getDate()
    {
        return $this->_date;
    }
    
    /**
     *
     * @param \PhpGedcom\Record\Head\Gedc $gedc
     */
    public function setGedc(\PhpGedcom\Record\Head\Gedc $gedc)
    {
        $this->_gedc = $gedc;
    }
    
    /**
     *
     * @return \PhpGedcom\Record\Head\Gedc
     */
    public function getGedc()
    {
        return $this->_gedc;
    }
    
    /**
     *
     * @param \PhpGedcom\Record\Head\Char $char
     */
    public function setChar(\PhpGedcom\Record\Head\Char $char)
    {
        $this->_char = $char;
    }
    
    /**
     *
     * @return \PhpGedcom\Record\Head\Char
     */
    public function getChar()
    {
        return $this->_char;
    }
    
    /**
     *
     * @param \PhpGedcom\Record\Head\Plac $plac
     */
    public function setPlac(\PhpGedcom\Record\Head\Plac $plac)
    {
        $this->_plac = $plac;
    }
    
    /**
     *
     * @return \PhpGedcom\Record\Head\Plac
     */
    public function getPlac()
    {
        return $this->_plac;
    }
}
