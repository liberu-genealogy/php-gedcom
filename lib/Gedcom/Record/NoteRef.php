<?php
/**
 *
 */

namespace Gedcom\Record;

/**
 *
 */
class NoteRef extends \Gedcom\Record
{
    /**
     *
     */
    protected $_isRef   = false;
    
    /**
     *
     */
    protected $_note    = '';
    
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
