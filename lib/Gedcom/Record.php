<?php

namespace Gedcom;

if(!class_exists('\Gedcom\Record')):

/**
 *
 */
abstract class Record
{
    public $refId = null;
    
    public $notes = array();
    public $noteReferences = array();
    
    public $objects = array();
    public $objectReferences = array();
    
    /**
     *
     */
    public function addNoteReference(\Gedcom\Record\Note\Reference &$reference)
    {
        $this->noteReferences[] = $reference;
    }
    
    /**
     *
     */
    public function addNote(\Gedcom\Record\Note\Text &$note)
    {
        $this->notes[] = $note;
    }
    
    /**
     *
     */
    public function addObject(\Gedcom\Record\Object\Embedded &$object)
    {
        $this->objects[] = $object;
    }
    
    /**
     *
     */
    public function addObjectReference(\Gedcom\Record\Object\Reference &$object)
    {
        $this->objectReferences[] = $object;
    }
}

endif;
