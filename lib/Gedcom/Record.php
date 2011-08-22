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
    public $noteRef = array();
    
    public $objects = array();
    public $objectRef = array();
    
    public $sourceCitations = array();
    public $sourceCitationRef = array();
    
    
    /**
     *
     */
    public function addNoteRef(\Gedcom\Record\Note\Ref &$ref)
    {
        $this->noteRef[] = $ref;
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
    public function addObjeRef(\Gedcom\Record\Obje\Ref &$object)
    {
        $this->objectRef[] = $object;
    }
    
    /**
     *
     */
    public function addSourceCitation(\Gedcom\Record\SourceCitation\Embe &$citation)
    {
        $this->sourceCitations[] = $citation;
    }
    
    /**
     *
     */
    public function addSourceCitationRef(\Gedcom\Record\SourceCitation\Ref &$citation)
    {
        $this->sourceCitationRef[] = $citation;
    }
}

endif;
