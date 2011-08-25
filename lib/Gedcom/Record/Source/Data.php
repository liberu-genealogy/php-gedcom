<?php
/**
 *
 */

namespace Gedcom\Record\Source;

/**
 *
 */
class Data extends \Gedcom\Record
{
    public $events = array();
    protected $_agnc = null;
    protected $_date = null;
    
    protected $_text = array();
    
    /**
     *
     */
    public function addText($text)
    {
        $this->_text[] = $text;
    }
}

