<?php
/**
 *
 */

namespace Gedcom\Record;

use \Gedcom\Record\Sourceable;

/**
 *
 */
class NoteRef extends \Gedcom\Record implements Sourceable
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
    protected $_sour = array();
    
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
    public function addSour(\Gedcom\Record\SourRef &$sour)
    {
        $this->_sour[] = &$sour;
    }
}
