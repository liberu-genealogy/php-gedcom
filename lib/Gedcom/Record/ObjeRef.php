<?php
/**
 *
 */

namespace Gedcom\Record;

/**
 *
 */
class ObjeRef extends \Gedcom\Record
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
    protected $_title   = null;
    
    /**
     *
     */
    protected $_file    = null;
    
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
}

