<?php

namespace Gedcom;

if(!class_exists('\Gedcom\Record')):

/**
 *
 */
abstract class Record
{
    public $refId = null;
    
    public $chan = null;
    
    public $notes = array();
    public $noteReferences = array();
    
    public $objects = array();
    public $objectReferences = array();
    
    public $sourceCitations = array();
    public $sourceCitationReferences = array();
    
    
    /**
     *
     */
    public function addNoteRef(\Gedcom\Record\Note\Ref &$reference)
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
    public function addObje(\Gedcom\Record\Obje\Embe &$object)
    {
        $this->objects[] = $object;
    }
    
    /**
     *
     */
    public function addObjectRef(\Gedcom\Record\Obje\Ref &$object)
    {
        $this->objectReferences[] = $object;
    }
    
    /**
     *
     */
    public function addSourceCitation(\Gedcom\Record\SourceCitation\Embedded &$citation)
    {
        $this->sourceCitations[] = $citation;
    }
    
    /**
     *
     */
    public function addSourceCitationReference(\Gedcom\Record\SourceCitation\Reference &$citation)
    {
        $this->sourceCitationReferences[] = $citation;
    }
}

endif;
