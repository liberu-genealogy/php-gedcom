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

namespace Gedcom\Record\Head;

/**
 *
 */
class Sour extends \Gedcom\Record
{
    /**
     *
     */
    protected $_sour = null;
    
    /**
     *
     */
    protected $_vers = null;
    
    /**
     *
     */
    protected $_name = null;
    
    /**
     *
     */
    protected $_corp = null;
    
    /**
     *
     */
    protected $_data = null;
    
    /**
     * 
     * @param \Gedcom\Record\Head\Sour\Corp $sour 
     */
    public function setCorp(\Gedcom\Record\Head\Sour\Corp $corp)
    {
        $this->_corp = $corp;
    }
    
    /**
     *
     * @return \Gedcom\Record\Head\Sour\Corp
     */
    public function getCorp()
    {
        return $this->_corp;
    }
    
    /**
     * 
     * @param \Gedcom\Record\Head\Sour\Data $data 
     */
    public function setData(\Gedcom\Record\Head\Sour\Data $data)
    {
        $this->_data = $data;
    }
    
    /**
     *
     * @return \Gedcom\Record\Head\Sour\Data
     */
    public function getData()
    {
        return $this->_data;
    }
}
