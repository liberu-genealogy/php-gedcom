<?php
/**
 *
 */

namespace Gedcom\Record;

/**
 *
 */
class ObjeRef extends \Gedcom\Record implements Noteable
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
    public function addNote(\Gedcom\Record\NoteRef &$note)
    {
        $this->_note[] = &$note;
    }
}

