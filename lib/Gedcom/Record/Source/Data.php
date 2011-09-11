<?php
/**
 *
 */

namespace Gedcom\Record\Source;

use \Gedcom\Record\Noteable;

/**
 *
 */
class Data extends \Gedcom\Record implements Noteable
{
    public $events = array();
    protected $_agnc = null;
    protected $_date = null;
    
    protected $_text = array();
    
    /**
     *
     */
    protected $_note = array();
    
    /**
     *
     */
    public function addText($text)
    {
        $this->_text[] = $text;
    }
    
    /**
     *
     */
    public function addNote(\Gedcom\Record\NoteRef &$note)
    {
        $this->_note[] = &$note;
    }
}
